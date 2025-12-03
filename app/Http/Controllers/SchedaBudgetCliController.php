<?php
namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use knet\ArcaModels\Agent;
use knet\ArcaModels\BudgAna;
use knet\ArcaModels\DocCli;
use knet\ArcaModels\DocRow;
use knet\Helpers\AgentFltUtils;
use knet\Helpers\PdfReport;
use knet\Helpers\Utils;
use knet\Helpers\RedisUser;

class SchedaBudgetCliController extends Controller
{
		protected $thisYear;
		protected $prevYear;	
		protected $dStartMonth; 	
		protected $dEndMonth;
		protected $arrayIDOC;
		protected $arrayIDBO;
		protected $arrayIDFT;
		protected $arrayIDprevFT;

    public function __construct(){
      $this->middleware('auth');
    }

	public function downloadBudgAnaPDF(Request $req, $year, $codAg=null)
	{
		//Let's Set the Date
		$thisYear = Carbon::now()->year;
		$startDateFT = Carbon::createFromDate($year, 1, 1);
		if ($year == $thisYear) {
			$endDateFT = new Carbon('last day of last month');
		} else {
			$endDateFT = Carbon::createFromDate($year, 12, 31);
		}

		$fltAgents = ($req->input('fltAgents')) ? $req->input('fltAgents') : ($codAg ? array_wrap($codAg) : $codAg);
		// $dataFineAgente = Carbon::createFromDate($this->prevYear, 1, 1);
		$agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereIn('codice', $fltAgents)->orderBy('codice')->get();
		// $agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', $dataFineAgente)->orderBy('codice')->get();
		$fltAgents = AgentFltUtils::checkSpecialRules($fltAgents);

		$allCliBudgets = BudgAna::where('esercizio', (string)$year)->where('propcontra', true);
		// if (!in_array(RedisUser::get('role'), ['agent', 'superAgent'])) {
			$allCliBudgets->whereHas('client', function ($query) use($fltAgents) {
                        $query->whereIn('agente', $fltAgents);
                    });
			// }
		$allCliBudgets = $allCliBudgets->with(['client'])->get();
		
		$allFattCli = $this->getInvoice($year, ['FT', 'FE', 'NC', 'NE', 'EQ', 'EF', 'NB'], ['A', 'B', 'D0'], $fltAgents, ['Z'])->sortBy('doccli.codicecf')->groupBy('doccli.codicecf')->mapWithKeys(function ($group, $key) {
			return collect([
				$key =>
				collect([
					'codicecf' => $key,
					'client' => $group->first()->doccli->client,
					'totFat' => $group->sum('totRowPrice'),
					'n_docFat' => $group->groupBy('doccli.id')->count()
				])
			]);
		});
		// dd($allCliBudgets->first()->codice);
		// dd($allFattCli->get($allCliBudgets->first()->codice));
		// dd(implode(', ', $agents->pluck('descrizion')->toArray()));
		
		$title = "Situazione Budget Clienti - " . (string)$year;
		$subTitle = implode(', ',$agents->pluck('descrizion')->toArray());
		$view = '_exports.pdf.schedaBudgetCliPdf';
		$data = [
			'agents' => $agents,
			'descrAg' => $subTitle,
			'thisYear' => $thisYear,
			'ditta' => RedisUser::get('ditta_DB'),
			'startDateFT' => $startDateFT,
			'endDateFT' => $endDateFT,
			'allCliBudgets' => $allCliBudgets,
			'allFattCli' => $allFattCli,
		];
		$pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

		return $pdf->stream($title . '-' . $subTitle . '.pdf');
	}


	/* Restituisce tutte le fatture in funzione di alcune condizioni
		1. $agents -> Array di Agenti [Optional]
		2. $filiali -> Boolean [Default -> false]
		3. $grupIn -> Array con iniziale del prodotti es. ['A', 'B', 'B06'] 
	 */
	public function getInvoice($year, $listTipoDoc, $grupIn=[], $agents=[], $grupNotIn=[], $filiali=false){
		// Mi costruisco l'array delle teste dei documenti da cercare
		if(empty($this->arrayIDFT)){
			$docTes = DocCli::select('id')							
								->whereIn('esercizio', [(string)$year])
								->whereIn('tipodoc', $listTipoDoc);
			if(!$filiali && RedisUser::get('ditta_DB')=='knet_it'){					
				$docTes->whereNotIn('codicecf',['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
			}
			if(!empty($agents)){
				$docTes->whereIn('agente', $agents);
			}
			// $docTes->whereBetween('datadoc', [$this->dStartMonth, $this->dEndMonth]);
			$docTes = $docTes->get();
			$this->arrayIDFT = $docTes->toArray();
		}
		
		//Costruisco infine le righe con i dati che mi servono
		$docRow = DocRow::select('id_testa', 'codicearti', 'prezzoun', 'prezzotot', 'sconti', 'quantitare', 'quantita')
							->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
							->with(['doccli' => function($q){
								$q->select('id', 'tipomodulo', 'codicecf', 'sconti', 'scontocass', 'numerodoc')->with('client');
							}])
							->whereHas('product', function($q) {
								$q->orWhere('u_artlis',1)->orWhere('u_perscli',1);
							})
							->whereRaw('(LEFT(codicearti,4) != ? AND LEFT(codicearti,4) != ?)', ['CAMP', 'NOTA'])
							->where('quantitare', '>', 0)
							->where('ommerce', 0)
							->where('codicearti', '!=', '');
		if(!empty($grupIn)){
			$docRow->where(function ($q) use ($grupIn){
				$q->where('gruppo', 'like', $grupIn[0].'%');
				if(count($grupIn)>1){
					for($i=1; $i<count($grupIn); $i++){
						$q->orWhere('gruppo', 'like', $grupIn[$i].'%');
					}
				}
			});
		}
		if (!empty($grupNotIn)) {
			$docRow->where(function ($q) use ($grupNotIn) {
				$q->where('gruppo', 'NOT LIKE', $grupNotIn[0] . '%');
				if (count($grupNotIn) > 1) {
					for ($i = 1; $i < count($grupNotIn); $i++) {
						$q->where('gruppo', 'NOT LIKE', $grupNotIn[$i] . '%');
					}
				}
			});
		}
		$docRow = $docRow->whereIn('id_testa', $this->arrayIDFT)->get();
		
		$docRow = $this->calcTotRowPrice($docRow);
		return $docRow;
	}

	/* 
		Calcola il Prezzo totale di ogni riga applicando tutti gli sconti del documento
		inclusi Extra Sconti Cassa o Merce
	 */
	public function calcTotRowPrice($collect, $usePrezzoUn = false){
		foreach ($collect as $row){
			$fattoreMolt = ($row->doccli->tipomodulo == 'N' ? -1 : 1);
			if($usePrezzoUn){
				$unitRowPrice = Utils::scontaDel(Utils::scontaDel(Utils::scontaDel($row->prezzoun, $row->sconti, 4), $row->doccli->sconti, 3), $row->doccli->scontocass, 3);
				$row->totRowPrice = (float)round(($unitRowPrice*$row->quantitare*$fattoreMolt),2);
			} else {
				$totRowPrice = Utils::scontaDel(Utils::scontaDel($row->prezzotot, $row->doccli->sconti, 3), $row->doccli->scontocass, 3);
				$row->totRowPrice = (float)round($totRowPrice*$fattoreMolt, 2);
			}
		}
		return $collect;
	}
}