<?php
namespace knet\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use knet\ArcaModels\DocCli;
use knet\ArcaModels\DocRow;

class DocRowUtils
{

    protected $docFilter;
    protected $arrayIdTes;

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
        // $docTes = DocCli::select('id');
        // if (!empty($this->docFilter->getFilters())) {
        //     $docTes = $this->docFilter->queryBuilder($docTes);
        //     } else {
        // return null;
        // }
        // $docTes = $docTes->get();
        // $this->arrayIdTes = $docTes->toArray();

        //Costruisco infine le righe con i dati che mi servono
        $docRow = DocRow::select('*')->addSelect(DB::raw('prezzoun*0 as totRowGrossPrice'))->addSelect(DB::raw('prezzoun*0 as totRowNetPrice'))
            ->with(['doccli' => function ($q) {
                // $q->select('id', 'tipomodulo', 'codicecf', 'sconti', 'scontocass', 'numerodoc')->with('client');
                $q->with('client', 'agent');
            }, 'product']);
        if (!empty($this->docFilter->getFilters())) {
            $docRow = $this->docFilter->queryBuilder($docRow);
        } else {
            return null;
        }
        // $docRow = $docRow->whereIn('id_testa', $this->arrayIDBO)->get();
        // dd($docRow);
        $docRow = $docRow->get();

        $docRow = $this->calcTotRowPrice($docRow);
        return $docRow;
    }

    public function calcTotRowPrice($collect, $usePrezzoUn = true)
    {
        foreach ($collect as $docrow) {
            $fattoreMolt = ($docrow->doccli->tipomodulo == 'N' ? -1 : 1);
            if ($usePrezzoUn) {
                $unitGrossRowPrice = Utils::scontaDel($docrow->prezzoun, $docrow->sconti, 4);
                $docrow->totRowGrossPrice = (float)round(($unitGrossRowPrice * $docrow->quantitare * $fattoreMolt), 2);
                $unitNetPrice = Utils::scontaDel(Utils::scontaDel($unitGrossRowPrice, $docrow->doccli->sconti, 3), $docrow->doccli->scontocass, 3);
                $docrow->totRowNetPrice = (float)round(($unitNetPrice * $docrow->quantitare * $fattoreMolt), 2);
            } else {
                $unitGrossRowPrice = Utils::scontaDel($docrow->prezzotot, $docrow->sconti, 4);
                $docrow->totRowGrossPrice = (float)round(($unitGrossRowPrice * $fattoreMolt), 2);
                $unitNetPrice = Utils::scontaDel(Utils::scontaDel($unitGrossRowPrice, $docrow->doccli->sconti, 3), $docrow->doccli->scontocass, 3);
                $docrow->totRowNetPrice = (float)round(($unitNetPrice * $fattoreMolt), 2);
            }
        }
        return $collect;
    }

    public static function collectByClientTipoModulo($collect){
        // dd($totVal);
        $newCollect = $collect->sortBy('doccli.codicecf')->groupBy('doccli.codicecf')->mapWithKeys(function ($group, $key) {
            return collect([
                $key =>
                collect([
                    'codicecf' => $key,
                    'client' => $group->first()->doccli->client,
                    // 'totOrd' => $group->sum('totRowPrice'),
                    // 'n_docOrd' => $group->groupBy('doccli.id')->count(),
                    'totNetVal' => $group->sortBy('doccli.tipomodulo')->groupBy('doccli.tipomodulo')->mapWithKeys(
                        fn($docrows, $tipoDoc) => collect([
                            $tipoDoc =>
                            $docrows->sum('totRowNetPrice')
                        ])
                    ),
                    'totGrossVal' => $group->sortBy('doccli.tipomodulo')->groupBy('doccli.tipomodulo')->mapWithKeys(
                        fn($docrows, $tipoDoc) => collect([
                            $tipoDoc =>
                            $docrows->sum('totRowGrossPrice')
                        ])
                    ),
                    'n_doc' => $group->sortBy('doccli.tipomodulo')->groupBy('doccli.tipomodulo')->mapWithKeys(
                        fn($docrows, $tipoDoc) => collect([
                            $tipoDoc =>
                            $docrows->groupBy('doccli.id')->count()
                        ])
                    ),
                    'docs' => $group->sortBy('doccli.tipomodulo')->groupBy('doccli.tipomodulo')->mapWithKeys(
                        fn($docrows, $tipoDoc) => collect([
                            $tipoDoc =>
                            $docrows->groupBy('doccli.id')->map(
                                fn($rows) => collect([
                                    'head' => $rows->first()->doccli,
                                    'rows' => $rows
                                ])
                            )
                        ])
                    )
                ])
            ]);
        });
        // dd($newCollect->first());
        return $newCollect;
    }

    public static function collectByAgentTipoModulo($collect)
    {
        // dd($totVal);
        $newCollect = $collect->sortBy('doccli.agente')->groupBy('doccli.agente')->mapWithKeys(function ($group, $key) {
            return collect([
                $key =>
                collect([
                    'codAgente' => $key,
                    'agente' => $group->first()->doccli->agente,
                    // 'totOrd' => $group->sum('totRowPrice'),
                    // 'n_docOrd' => $group->groupBy('doccli.id')->count(),
                    'totNetVal' => $group->sortBy('doccli.tipomodulo')->groupBy('doccli.tipomodulo')->mapWithKeys(
                        fn($docrows, $tipoDoc) => collect([
                            $tipoDoc =>
                            $docrows->sum('totRowNetPrice')
                        ])
                    ),
                    'totGrossVal' => $group->sortBy('doccli.tipomodulo')->groupBy('doccli.tipomodulo')->mapWithKeys(
                        fn($docrows, $tipoDoc) => collect([
                            $tipoDoc =>
                            $docrows->sum('totRowGrossPrice')
                        ])
                    ),
                    'n_doc' => $group->sortBy('doccli.tipomodulo')->groupBy('doccli.tipomodulo')->mapWithKeys(
                        fn($docrows, $tipoDoc) => collect([
                            $tipoDoc =>
                            $docrows->groupBy('doccli.id')->count()
                        ])
                    ),
                    'docs' => $group->sortBy('doccli.tipomodulo')->groupBy('doccli.tipomodulo')->mapWithKeys(
                        fn($docrows, $tipoDoc) => collect([
                            $tipoDoc =>
                            $docrows->groupBy('doccli.id')->map(
                                fn($rows) => collect([
                                    'head' => $rows->first()->doccli,
                                    'rows' => $rows
                                ])
                            )
                        ])
                    )
                ])
            ]);
        });
        // dd($newCollect->first());
        return $newCollect;
    }

    public function collectByTipoModulo($collect)
    {
        $collect->sortBy('doccli.tipomodulo')->groupBy('doccli.tipomodulo')->mapWithKeys(function ($docs, $tipoDoc) {
            return collect([
                $tipoDoc =>
                $docs->groupBy('doccli.id')->map(
                    fn($rows) => collect([
                        'head' => $rows->first()->doccli,
                        'rows' => $rows
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
        });
        // ->sortBy('codicecf');
        // ->sortByDesc('n_doc.F');
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