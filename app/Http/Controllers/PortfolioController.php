<?php
namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use knet\ArcaModels\Agent;
use knet\ArcaModels\DocCli;
use knet\ArcaModels\DocRow;
use knet\Helpers\Utils;
use knet\Helpers\RedisUser;

class PortfolioController extends Controller
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

    public function idxAg(Request $req, $codAg=null){
		// Costruisco i filtri
		$this->thisYear = (!$req->input('year') ? Carbon::now()->year : $req->input('year'));
		$this->prevYear = $this->thisYear-1;	
		$this->dStartMonth = new Carbon('first day of '.Carbon::now()->format('F').' '.((string)$this->thisYear)); 	
		$this->dEndMonth = new Carbon('last day of '.Carbon::now()->format('F').' '.((string)$this->thisYear));
		$mese = (!$req->input('mese') ? Carbon::now()->month : $req->input('mese'));
		if($mese){
			$this->dStartMonth = new Carbon('first day of '.Carbon::createFromDate(null, $mese, null)->format('F').' '.((string)$this->thisYear)); 
			$this->dEndMonth = new Carbon('last day of '.Carbon::createFromDate(null, $mese, null)->format('F').' '.((string)$this->thisYear));
		}

		$agents = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orderBy('codice')->get();
		$codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
        $fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agents->first()->codice)); //$agents->pluck('codice')->toArray();

		$OCKrona = $this->getOrderToShip(['A'], $fltAgents)->sum('totRowPrice');
		$OCKoblenz = $this->getOrderToShip(['B'], $fltAgents, ['B06'])->sum('totRowPrice');
		$OCKubica = $this->getOrderToShip(['B06'], $fltAgents, ['B0630'])->sum('totRowPrice');
		$OCAtomika = $this->getOrderToShip(['B0630'], $fltAgents)->sum('totRowPrice');
		$OCPlanet = (RedisUser::get('ditta_DB')=='kNet_es') ? $this->getOrderToShip(['D0'], $fltAgents)->sum('totRowPrice') : 0;
		$OCDIC = $this->getOrderToShip(['DIC'], $fltAgents)->sum('totRowPrice');

		$BOKrona = $this->getDdtNotInvoiced(['A'], $fltAgents)->sum('totRowPrice');
		$BOKoblenz = $this->getDdtNotInvoiced(['B'], $fltAgents, ['B06'])->sum('totRowPrice');
		$BOKubica = $this->getDdtNotInvoiced(['B06'], $fltAgents, ['B0630'])->sum('totRowPrice');
		$BOAtomika = $this->getDdtNotInvoiced(['B0630'], $fltAgents)->sum('totRowPrice');
		$BOPlanet = (RedisUser::get('ditta_DB')=='kNet_es') ? $this->getDdtNotInvoiced(['D0'], $fltAgents)->sum('totRowPrice') : 0;
		$BODIC = $this->getDdtNotInvoiced(['DIC'], $fltAgents)->sum('totRowPrice');

		$FTKrona = $this->getInvoice(['A'], $fltAgents)->sum('totRowPrice');
		$FTKoblenz = $this->getInvoice(['B'], $fltAgents, ['B06'])->sum('totRowPrice');
		$FTKubica = $this->getInvoice(['B06'], $fltAgents, ['B0630'])->sum('totRowPrice');
		$FTAtomika = $this->getInvoice(['B0630'], $fltAgents)->sum('totRowPrice');
		$FTPlanet = (RedisUser::get('ditta_DB')=='kNet_es') ? $this->getInvoice(['D0'], $fltAgents)->sum('totRowPrice') : 0;
		$FTDIC = $this->getInvoice(['DIC'], $fltAgents)->sum('totRowPrice');

		$FTPrevKrona = $this->getPrevInvoice(['A'], $fltAgents)->sum('totRowPrice');
		$FTPrevKoblenz = $this->getPrevInvoice(['B'], $fltAgents, ['B06'])->sum('totRowPrice');
		$FTPrevKubica = $this->getPrevInvoice(['B06'], $fltAgents, ['B0630'])->sum('totRowPrice');
		$FTPrevAtomika = $this->getPrevInvoice(['B0630'], $fltAgents)->sum('totRowPrice');
		$FTPrevPlanet = (RedisUser::get('ditta_DB')=='kNet_es') ? $this->getPrevInvoice(['D0'], $fltAgents)->sum('totRowPrice') : 0;
		$FTPrevDIC = $this->getPrevInvoice(['DIC'], $fltAgents)->sum('totRowPrice');
		// dd($fltAgents);
		return view('portfolio.idxAg', [
			'agents' => $agents,
			'mese' => $mese,
			'thisYear' => $this->thisYear,
			'prevYear' => $this->prevYear,
			'fltAgents' => $fltAgents,
			'OCKrona' => $OCKrona,
			'OCKoblenz' => $OCKoblenz,
			'OCKubica' => $OCKubica,
			'OCAtomika' => $OCAtomika,
			'OCPlanet' => $OCPlanet,
			'OCDIC' => $OCDIC,
			'BOKrona' => $BOKrona,
			'BOKoblenz' => $BOKoblenz,
			'BOKubica' => $BOKubica,
			'BOAtomika' => $BOAtomika,
			'BOPlanet' => $BOPlanet,
			'BODIC' => $BODIC,
			'FTKrona' => $FTKrona,
			'FTKoblenz' => $FTKoblenz,
			'FTKubica' => $FTKubica,
			'FTAtomika' => $FTAtomika,
			'FTPlanet' => $FTPlanet,
			'FTDIC' => $FTDIC,
			'FTPrevKrona' => $FTPrevKrona,
			'FTPrevKoblenz' => $FTPrevKoblenz,
			'FTPrevKubica' => $FTPrevKubica,
			'FTPrevAtomika' => $FTPrevAtomika,
			'FTPrevPlanet' => $FTPrevPlanet,
			'FTPrevDIC' => $FTPrevDIC,
			'urlOrders' => action('DocCliController@showOrderDispachMonth', ['fltAgents'=> $fltAgents, 'mese'=>$mese, 'year' => $this->thisYear]),
			'urlDdts' => action('DocCliController@showDdtToInvoice', ['fltAgents'=>$fltAgents]),
			'urlInvoices' => action('DocCliController@showInvoiceMonth', ['fltAgents'=>$fltAgents, 'mese'=>$mese, 'year' => $this->thisYear]),
		]);
	}

	public function portfolioPDF(Request $req, $codAg, $mese, $year){
		$this->thisYear = Carbon::now()->year;
		$this->prevYear = $this->thisYear-1;	
		$this->dStartMonth = new Carbon('first day of '.Carbon::now()->format('F').' '.((string)$this->thisYear)); 	
		$this->dEndMonth = new Carbon('last day of '.Carbon::now()->format('F').' '.((string)$this->thisYear));
		$mese = (!$req->input('mese') ? Carbon::now()->month : $req->input('mese'));
		if($mese){
			$this->dStartMonth = new Carbon('first day of '.Carbon::createFromDate(null, $mese, null)->format('F').' '.((string)$this->thisYear)); 
			$this->dEndMonth = new Carbon('last day of '.Carbon::createFromDate(null, $mese, null)->format('F').' '.((string)$this->thisYear));
		}

        $codAg = ($req->input('codag')) ? $req->input('codag') : ((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $codAg));
		
		$OCKrona = $this->getOrderToShip(['A', 'B', 'D0'], array_wrap($codAg));
		dd($OCKrona);
	}



	/* Restituisce tutti gli ordini che devono essere evasi in funzione di alcune condizioni
		1. $agents -> Array di Agenti [Optional]
		2. $filiali -> Boolean [Default -> false]
		3. $grupIn -> Array con iniziale del prodotti es. ['A', 'B', 'B06'] 
		4. $grupNotIn -> Array con iniziali dei gruppi prodotto da escludere [Optional]
	 */
	public function getOrderToShip($grupIn, $agents=[], $grupNotIn=[], $filiali=false){

		// Mi costruisco l'array delle teste dei documenti da cercare se Non esiste
		if(empty($this->arrayIDOC)){
			$docTes = DocCli::select('id')							
								->whereIn('esercizio', [(string)$this->thisYear, (string)$this->prevYear])
								->where('tipodoc', 'OC');
			if(!$filiali && RedisUser::get('ditta_DB')=='knet_it'){					
				$docTes->whereNotIn('codicecf',['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
			}
			if(!empty($agents)){
				$docTes->whereIn('agente', $agents);
			}
			$docTes = $docTes->get();
			$this->arrayIDOC = $docTes->toArray();
		}	
		
		//Costruisco infine le righe con i dati che mi servono
		$docRow = DocRow::select('id_testa', 'codicearti', 'descrizion', 'gruppo', 'prezzoun', 'sconti', 'quantitare', 'unmisura', 'quantita', 'u_dtpronto', 'dataconseg')
							->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
							->with(['doccli' => function($q){
								$q->select('id', 'tipomodulo', 'sconti', 'scontocass', 'numerodoc', 'tipodoc', 'datadoc', 'codicecf');
							}])
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
		if(!empty($grupNotIn)){
			$docRow->where(function ($q) use ($grupNotIn){
				$q->where('gruppo', 'NOT LIKE', $grupNotIn[0].'%');
				if(count($grupNotIn)>1){
					for($i=1; $i<count($grupNotIn); $i++){
						$q->orWhere('gruppo', 'NOT LIKE', $grupNotIn[$i].'%');
					}
				}
			});
		}
		// 26/11 Richiesta di Mauro per fare Ordini solo del mese!
		// $docRow->where('dataconseg', '<=', $this->dEndMonth);
		$docRow->whereBetween('dataconseg', [$this->dStartMonth, $this->dEndMonth]);
		$docRow = $docRow->whereIn('id_testa', $this->arrayIDOC)->get();
		
		$docRow = $this->calcTotRowPrice($docRow);
		return $docRow;
	}

	/* Restituisce tutte le bolle che devono essere fatturate in funzione di alcune condizioni
		1. $agents -> Array di Agenti [Optional]
		2. $filiali -> Boolean [Default -> false]
		3. $grupIn -> Array con iniziale del prodotti es. ['A', 'B', 'B06'] 
	 */
	public function getDdtNotInvoiced($grupIn, $agents=[], $grupNotIn=[], $filiali=false){
		// Mi costruisco l'array delle teste dei documenti da cercare
		if(empty($this->arrayIDBO)){
			$docTes = DocCli::select('id')							
								->whereIn('esercizio', [(string)$this->thisYear])
								->whereIn('tipodoc', ['BO', 'BR']);
			if(!$filiali && RedisUser::get('ditta_DB')=='knet_it'){					
				$docTes->whereNotIn('codicecf',['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
			}
			if(!empty($agents)){
				$docTes->whereIn('agente', $agents);
			}
			$docTes->whereBetween('datadoc', [$this->dStartMonth, $this->dEndMonth]);
			$docTes = $docTes->get();
			$this->arrayIDBO = $docTes->toArray();
		}
		
		//Costruisco infine le righe con i dati che mi servono
		$docRow = DocRow::select('id_testa', 'codicearti', 'prezzoun', 'sconti', 'quantitare')
							->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
							->with(['doccli' => function($q){
								$q->select('id', 'tipomodulo', 'sconti', 'scontocass', 'numerodoc');
							}])
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
		if(!empty($grupNotIn)){
			$docRow->where(function ($q) use ($grupNotIn){
				$q->where('gruppo', 'NOT LIKE', $grupNotIn[0].'%');
				if(count($grupNotIn)>1){
					for($i=1; $i<count($grupNotIn); $i++){
						$q->orWhere('gruppo', 'NOT LIKE', $grupNotIn[$i].'%');
					}
				}
			});
		}
		$docRow = $docRow->whereIn('id_testa', $this->arrayIDBO)->get();
		
		$docRow = $this->calcTotRowPrice($docRow);
		return $docRow;
	}

	/* Restituisce tutte le fatture in funzione di alcune condizioni
		1. $agents -> Array di Agenti [Optional]
		2. $filiali -> Boolean [Default -> false]
		3. $grupIn -> Array con iniziale del prodotti es. ['A', 'B', 'B06'] 
	 */
	public function getInvoice($grupIn, $agents=[], $grupNotIn=[], $filiali=false){
		// Mi costruisco l'array delle teste dei documenti da cercare
		if(empty($this->arrayIDFT)){
			$docTes = DocCli::select('id')							
								->whereIn('esercizio', [(string)$this->thisYear])
								->whereIn('tipodoc', ['FT', 'FE', 'NC', 'NE', 'EQ', 'EF']);
			if(!$filiali && RedisUser::get('ditta_DB')=='knet_it'){					
				$docTes->whereNotIn('codicecf',['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
			}
			if(!empty($agents)){
				$docTes->whereIn('agente', $agents);
			}
			$docTes->whereBetween('datadoc', [$this->dStartMonth, $this->dEndMonth]);
			$docTes = $docTes->get();
			$this->arrayIDFT = $docTes->toArray();
		}
		
		//Costruisco infine le righe con i dati che mi servono
		$docRow = DocRow::select('id_testa', 'codicearti', 'prezzoun', 'sconti', 'quantitare')
							->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
							->with(['doccli' => function($q){
								$q->select('id', 'tipomodulo', 'sconti', 'scontocass', 'numerodoc');
							}])
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
		if(!empty($grupNotIn)){
			$docRow->where(function ($q) use ($grupNotIn){
				$q->where('gruppo', 'NOT LIKE', $grupNotIn[0].'%');
				if(count($grupNotIn)>1){
					for($i=1; $i<count($grupNotIn); $i++){
						$q->orWhere('gruppo', 'NOT LIKE', $grupNotIn[$i].'%');
					}
				}
			});
		}
		$docRow = $docRow->whereIn('id_testa', $this->arrayIDFT)->get();
		
		$docRow = $this->calcTotRowPrice($docRow);
		return $docRow;
	}

	/* Fatture dell'anno precedente */
	public function getPrevInvoice($grupIn, $agents=[], $grupNotIn=[], $filiali=false){
		// Mi costruisco l'array delle teste dei documenti da cercare
		$dStartDate = $this->dStartMonth;
		$dEndDate = $this->dEndMonth;
		$dStartDate = $dStartDate->subYear();
		$dEndDate = $dEndDate->subYear();
		if(empty($this->arrayIDprevFT)){
			$docTes = DocCli::select('id')							
								->whereIn('esercizio', [(string)$this->prevYear])
								->whereIn('tipodoc', ['FT', 'FE', 'NC', 'NE', 'EQ', 'EF']);
			if(!$filiali && RedisUser::get('ditta_DB')=='knet_it'){					
				$docTes->whereNotIn('codicecf',['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
			}
			if(!empty($agents)){
				$docTes->whereIn('agente', $agents);
			}
			$docTes->whereBetween('datadoc', [$dStartDate, $dEndDate]);
			$docTes = $docTes->get();
			$this->arrayIDprevFT = $docTes->toArray();
		}
		//Costruisco infine le righe con i dati che mi servono
		$docRow = DocRow::select('id_testa', 'codicearti', 'prezzoun', 'sconti', 'quantitare')
							->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
							->with(['doccli' => function($q){
								$q->select('id', 'tipomodulo', 'sconti', 'scontocass', 'numerodoc');
							}])
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
		if(!empty($grupNotIn)){
			$docRow->where(function ($q) use ($grupNotIn){
				$q->where('gruppo', 'NOT LIKE', $grupNotIn[0].'%');
				if(count($grupNotIn)>1){
					for($i=1; $i<count($grupNotIn); $i++){
						$q->orWhere('gruppo', 'NOT LIKE', $grupNotIn[$i].'%');
					}
				}
			});
		}
		$docRow = $docRow->whereIn('id_testa', $this->arrayIDprevFT)->get();
		
		$docRow = $this->calcTotRowPrice($docRow);
		return $docRow;
	}

	/* 
		Calcola il Prezzo totale di ogni riga applicando tutti gli sconti del documento
		inclusi Extra Sconti Cassa o Merce
	 */
	public function calcTotRowPrice($collect){
		foreach ($collect as $row){
			$fattoreMolt = ($row->doccli->tipomodulo == 'N' ? -1 : 1);
			$unitRowPrice = Utils::scontaDel(Utils::scontaDel(Utils::scontaDel($row->prezzoun, $row->sconti, 2), $row->doccli->sconti, 2), $row->doccli->scontocass, 2);
			$row->totRowPrice = $unitRowPrice*$row->quantitare*$fattoreMolt;
		}
		return $collect;
	}

}