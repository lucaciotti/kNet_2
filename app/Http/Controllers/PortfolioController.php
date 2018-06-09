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

    public function __construct(){
      $this->middleware('auth');
    }

    public function idxAg(Request $req, $codAg=null){
		// Costruisco i filtri
		$this->thisYear = Carbon::now()->year;
		$this->prevYear = $this->thisYear-1;	
		$this->dStartMonth = new Carbon('first day of '.Carbon::now()->format('F').' '.((string)$this->thisYear)); 	
		$this->dEndMonth = new Carbon('last day of '.Carbon::now()->format('F').' '.((string)$this->thisYear));
		$mese = $req->input('mese');
		if($mese){
			$this->dStartMonth = new Carbon('first day of '.Carbon::createFromDate(null, $mese, null)->format('F').' '.((string)$this->thisYear)); 
			$this->dEndMonth = new Carbon('last day of '.Carbon::createFromDate(null, $mese, null)->format('F').' '.((string)$this->thisYear));
		}

		$agents = Agent::select('codice', 'descrizion')->orderBy('codice')->get();
        $codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
        $agente = (!empty($codAg)) ? $codAg : $agents->first()->codice;

		$docRowOC = $this->getOrderToShip(['A', 'B'], ['18']);
		$docRowBO = $this->getDdtNotInvoiced(['A', 'B'], ['18']);
		$docRowFT = $this->getInvoice(['A', 'B'], ['18']);
		
		dd($agente);

	}





	/* Restituisce tutti gli ordini che devono essere evasi in funzione di alcune condizioni
		1. $agents -> Array di Agenti [Optional]
		2. $filiali -> Boolean [Default -> false]
		3. $gruppo -> Array con iniziale del prodotti es. ['A', 'B', 'B06'] 
	 */
	public function getOrderToShip($gruppo, $agents=[], $filiali=false){
		// Mi costruisco l'array delle teste dei documenti da cercare
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
		
		//Costruisco infine le righe con i dati che mi servono
		$docRow = DocRow::select('id_testa', 'codicearti', 'prezzoun', 'sconti', 'quantitare')
							->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
							->with(['doccli' => function($q){
								$q->select('id', 'tipomodulo', 'sconti', 'scontocass', 'numerodoc');
							}])
							->where('quantitare', '>', 0)
							->where('ommerce', 0)
							->where('codicearti', '!=', '');
		if(!empty($gruppo)){
			$docRow->where(function ($q) use ($gruppo){
				$q->where('gruppo', 'like', $gruppo[0].'%');
				if(count($gruppo)>1){
					for($i=1; $i<count($gruppo); $i++){
						$q->orWhere('gruppo', 'like', $gruppo[$i].'%');
					}
				}
			});
		}
		$docRow->where('dataconseg', '<=', $this->dEndMonth);
		$docRow = $docRow->whereIn('id_testa', $docTes->toArray())->get();
		
		$docRow = $this->calcTotRowPrice($docRow);
		return $docRow;
	}

	/* Restituisce tutte le bolle che devono essere fatturate in funzione di alcune condizioni
		1. $agents -> Array di Agenti [Optional]
		2. $filiali -> Boolean [Default -> false]
		3. $gruppo -> Array con iniziale del prodotti es. ['A', 'B', 'B06'] 
	 */
	public function getDdtNotInvoiced($gruppo, $agents=[], $filiali=false){
		// Mi costruisco l'array delle teste dei documenti da cercare
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
		
		//Costruisco infine le righe con i dati che mi servono
		$docRow = DocRow::select('id_testa', 'codicearti', 'prezzoun', 'sconti', 'quantitare')
							->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
							->with(['doccli' => function($q){
								$q->select('id', 'tipomodulo', 'sconti', 'scontocass', 'numerodoc');
							}])
							->where('quantitare', '>', 0)
							->where('ommerce', 0)
							->where('codicearti', '!=', '');
		if(!empty($gruppo)){
			$docRow->where(function ($q) use ($gruppo){
				$q->where('gruppo', 'like', $gruppo[0].'%');
				if(count($gruppo)>1){
					for($i=1; $i<count($gruppo); $i++){
						$q->orWhere('gruppo', 'like', $gruppo[$i].'%');
					}
				}
			});
		}
		$docRow = $docRow->whereIn('id_testa', $docTes->toArray())->get();
		
		$docRow = $this->calcTotRowPrice($docRow);
		return $docRow;
	}

	/* Restituisce tutte le fatture in funzione di alcune condizioni
		1. $agents -> Array di Agenti [Optional]
		2. $filiali -> Boolean [Default -> false]
		3. $gruppo -> Array con iniziale del prodotti es. ['A', 'B', 'B06'] 
	 */
	public function getInvoice($gruppo, $agents=[], $filiali=false){
		// Mi costruisco l'array delle teste dei documenti da cercare
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
		
		//Costruisco infine le righe con i dati che mi servono
		$docRow = DocRow::select('id_testa', 'codicearti', 'prezzoun', 'sconti', 'quantitare')
							->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
							->with(['doccli' => function($q){
								$q->select('id', 'tipomodulo', 'sconti', 'scontocass', 'numerodoc');
							}])
							->where('quantitare', '>', 0)
							->where('ommerce', 0)
							->where('codicearti', '!=', '');
		if(!empty($gruppo)){
			$docRow->where(function ($q) use ($gruppo){
				$q->where('gruppo', 'like', $gruppo[0].'%');
				if(count($gruppo)>1){
					for($i=1; $i<count($gruppo); $i++){
						$q->orWhere('gruppo', 'like', $gruppo[$i].'%');
					}
				}
			});
		}
		$docRow = $docRow->whereIn('id_testa', $docTes->toArray())->get();
		
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


