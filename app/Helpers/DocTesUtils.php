<?php
namespace knet\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use knet\ArcaModels\DocCli;

class DocTesUtils
{

    protected $docFilter;

    public function __construct($docFilter = null)
    {
        if (!empty($docFilter) && $docFilter instanceof DocFilters){
            $this->docFilter = clone $docFilter;
        }  else {
            $this->docFilter = new DocFilters();
        }
    }

    public function getDocFilter() {
        return $this->docFilter;
    }

    public function getDocs() {
        $docs = DocCli::with(['docrow' => function ($q) {
            $q->select('*')->addSelect(DB::raw('prezzoun*0 as totRowGrossPrice'))->addSelect(DB::raw('prezzoun*0 as totNetGrossPrice'))->with(['product']);
        }, 'client']);
        if (!empty($this->docFilter->getFilters())) {
            $docs = $this->docFilter->queryBuilder($docs);
        } else {
            return null;
        }
        $docs = $docs->get();
        dd($docs);
        $docs = $this->calcTotRowPrice($docs);

        return $docs;
    }

    public function calcTotRowPrice($collect, $usePrezzoUn = true)
    {
        foreach ($collect as $row) {
            $fattoreMolt = ($row->tipomodulo == 'N' ? -1 : 1);
            foreach ($row->docrow as $docrow) {
                if ($usePrezzoUn) {
                    $unitGrossRowPrice = Utils::scontaDel($docrow->prezzoun, $docrow->sconti, 4);
                    $docrow->totRowGrossPrice = (float)round(($unitGrossRowPrice * $docrow->quantitare * $fattoreMolt), 2);
                    $unitNetPrice = Utils::scontaDel(Utils::scontaDel($unitGrossRowPrice, $row->sconti, 3), $row->scontocass, 3);
                    $docrow->totNetGrossPrice = (float)round(($unitNetPrice * $docrow->quantitare * $fattoreMolt), 2);
                } else {
                    $unitGrossRowPrice = Utils::scontaDel($docrow->prezzotot, $docrow->sconti, 4);
                    $docrow->totRowGrossPrice = (float)round(($unitGrossRowPrice * $fattoreMolt), 2);
                    $unitNetPrice = Utils::scontaDel(Utils::scontaDel($unitGrossRowPrice, $row->sconti, 3), $row->scontocass, 3);
                    $docrow->totNetGrossPrice = (float)round(($unitNetPrice * $fattoreMolt), 2);
                }
            }
        }
        return $collect;
    }

    public function collectByClientTipoModulo($collect){
        // dd($totVal);
        $newCollect = $collect->sortBy('codicecf')->groupBy('codicecf')->mapWithKeys(function ($group, $key){

            $totValO = 0;
            $totValB = 0;
            $totValF = 0;
            foreach ($group as $head) {
                foreach ($head->docrow as $row) {
                    if (in_array($head->tipomodulo, ['O'])) $totValO += $row->totNetGrossPrice;
                    if (in_array($head->tipomodulo, ['B'])) $totValB += $row->totNetGrossPrice;
                    if (in_array($head->tipomodulo, ['F', 'N'])) $totValF += $row->totNetGrossPrice;
                }
            }

			return collect([
				$key =>
				collect([
					'codicecf' => $key,
					'client' => $group->first()->client,
					'totValO' => $totValO,
					'totValB' => $totValB,
					'totValF' => $totValF,
					// 'n_docFat' => $group->groupBy('doccli.id')->count(),
					'docs' => $group->sortBy('tipomodulo')->groupBy('tipomodulo')->mapWithKeys(
						fn($docs, $tipoDoc) => collect([
                            $tipoDoc =>
							$docs->groupBy('id')->map(
								fn($rows) => collect([
									'head' => $rows->first(),
                                    'rows' => $rows->first()->docrow
                                ]))
						]))
				])
			]);
		});
        // dd($newCollect->first());
        return $newCollect;
    }

    public function collectByTipoModulo($collect)
    {
        $collect->sortBy('tipomodulo')->groupBy('tipomodulo')->mapWithKeys(function ($docs, $tipoDoc) {
            return collect([
                $tipoDoc =>
                $docs->groupBy('id')->map(
                    fn($rows) => collect([
                        'head' => $rows->first(),
                        'rows' => $rows->docrow
                    ])
                )
            ]);
        });
    }

    public static function buildDocsPortfolio($ordDocs, $fattDocs, $ddtDocs) {
        $portfolio = $ordDocs->union($fattDocs)->union($ddtDocs)->map(function ($c, $key) use ($fattDocs, $ddtDocs) {
            if ($fattDocs->has($key)) {
                $newC = $c->union($fattDocs[$key])->map(function ($v, $k) use ($fattDocs, $key) {
                    if ($v instanceof \Illuminate\Database\Eloquent\Collection || $v instanceof \Illuminate\Support\Collection) {
                        if ($fattDocs[$key]->has($k)) return $v->union($fattDocs[$key][$k]);
                    }
                    return $v;
                });
                return $newC;
            } else {
                return $c;
            }
            if ($ddtDocs->has($key)) {
                $newC = $c->union($ddtDocs[$key])->map(function ($v, $k) use ($ddtDocs, $key) {
                    if ($v instanceof \Illuminate\Database\Eloquent\Collection || $v instanceof \Illuminate\Support\Collection) {
                        if ($ddtDocs[$key]->has($k)) return $v->union($ddtDocs[$key][$k]);
                    }
                    return $v;
                });
                return $newC;
            }
            return $c;
        })
        // ->sortBy('codicecf');
        ->sortByDesc('totValF');
        return $portfolio;
    }

    /* Restituisce tutti gli ordini che devono essere evasi in funzione di alcune condizioni*/
    public function getOrderToShip($dEndMonth = null)
    {
        $year = Carbon::now()->year; 
        if ($dEndMonth == null) {
            $dEndMonth = new Carbon('last day of ' . Carbon::now()->format('F') . ' ' . ((string)$year));
        }

        if (empty($this->docFilter->getFilters())){
            $this->docFilter = new DocFilters();
            $this->docFilter->addArrayFilter('prGroupIncl', ['A', 'B', 'D0']);
            $this->docFilter->addArrayFilter('prGroupExcl', ['Z']);
            $this->docFilter->addBoolFilter('u_artlis&u_perslis', 1);
            $this->docFilter->addBoolFilter('ommerce', 0);
            $this->docFilter->addBoolFilter('filiali', 0);
            $this->docFilter->addStringFilter('codicearti', 'notEql', '');
        }

        $this->docFilter->addArrayFilter('tipodoc', 'OC');
        $this->docFilter->addNumFilter('quantitare', 'plus', 0);
        $this->docFilter->addDateFilter('dataconseg', 'before', $dEndMonth);

        $docs = $this->getDocs();
        return $this->collectByClientTipoModulo($docs);
    }

    /* Restituisce tutte le bolle che devono essere fatturate in funzione di alcune condizioni*/
    public function getDdtNotInvoiced($year=null, $dStartMonth=null, $dEndMonth=null)
    {
        if ($year == null) {
            $year = Carbon::now()->year;
        }

        if (empty($this->docFilter->getFilters())) {
            $this->docFilter = new DocFilters();
            $this->docFilter->addArrayFilter('prGroupIncl', ['A', 'B', 'D0']);
            $this->docFilter->addArrayFilter('prGroupExcl', ['Z']);
            $this->docFilter->addBoolFilter('u_artlis&u_perslis', 1);
            $this->docFilter->addBoolFilter('ommerce', 0);
            $this->docFilter->addBoolFilter('filiali', 0);
            $this->docFilter->addStringFilter('codicearti', 'notEql', '');
        }
        $this->docFilter->addArrayFilter('tipodoc', ['BO', 'BR']);
        $this->docFilter->addArrayFilter('esercizio', $year);
        $this->docFilter->addNumFilter('quantitare', 'plus', 0);
        if (!empty($dStartMonth) && !empty($dEndMonth)){
            $this->docFilter->addDateFilter('datadoc', 'between', $dStartMonth, $dEndMonth);
        }

        $docs = $this->getDocs();
        return $this->collectByClientTipoModulo($docs);
    }

    /* Restituisce tutte le fatture in funzione di alcune condizioni */
    public function getInvoice($year = null, $dStartMonth = null, $dEndMonth = null)
    {
        if ($year == null) {
            $year = Carbon::now()->year;
        }

        if (empty($this->docFilter->getFilters())) {
            $this->docFilter = new DocFilters();
            $this->docFilter->addArrayFilter('prGroupIncl', ['A', 'B', 'D0']);
            $this->docFilter->addArrayFilter('prGroupExcl', ['Z']);
            $this->docFilter->addBoolFilter('u_artlis&u_perslis', 1);
            $this->docFilter->addBoolFilter('ommerce', 0);
            $this->docFilter->addBoolFilter('filiali', 0);
            $this->docFilter->addStringFilter('codicearti', 'notEql', '');
        }
        $this->docFilter->addArrayFilter('tipodoc', ['FT', 'FE', 'NC', 'NE', 'EQ', 'EF', 'NB', 'NX']);
        $this->docFilter->addArrayFilter('esercizio', $year);
        if (!empty($dStartMonth) && !empty($dEndMonth)) {
            $this->docFilter->addDateFilter('datadoc', 'between', $dStartMonth, $dEndMonth);
        }

        $docs = $this->getDocs();
        return $this->collectByClientTipoModulo($docs);
    }

    // LISTA DEI DOCUMENTI
    // public function getListDoc($tipodocs, $agents = [], $evasi = false, $filiali = false)
    // {
    //     $docTes = DocCli::whereIn('tipodoc', $tipodocs);
    //     if (!$evasi) {
    //         $docTes->whereHas('docrow', function ($query) {
    //             $query->where('quantitare', '>', 0)
    //                 ->where('ommerce', 0)
    //                 ->where('codicearti', '!=', '')
    //                 ->whereHas('product', function ($q) {
    //                     $q->orWhere('u_artlis', 1)->orWhere('u_perscli', 1);
    //                 });
    //         });
    //     }
    //     if (in_array("OC", $tipodocs) || in_array("XC", $tipodocs)) {
    //         $docTes->whereHas('docrow', function ($query) {
    //             $query->where('dataconseg', '<=', $this->dEndMonth);;
    //         })
    //             ->whereIn('esercizio', [(string)$this->thisYear, (string)$this->prevYear]);
    //     } else {
    //         $docTes->whereBetween('datadoc', [$this->dStartMonth, $this->dEndMonth]);
    //     }
    //     if (!$filiali && RedisUser::get('ditta_DB') == 'knet_it') {
    //         $docTes->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
    //     }
    //     if (!empty($agents)) {
    //         $docTes->whereIn('agente', $agents);
    //     }
    //     $docTes = $docTes->with(['client', 'agent'])->orderBy('codicecf')->orderBy('datadoc', 'desc')->get();

    //     return $docTes;
    // }
}