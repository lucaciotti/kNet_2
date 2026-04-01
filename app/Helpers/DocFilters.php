<?php
namespace knet\Helpers;

use Illuminate\Database\Eloquent\Builder;
use knet\ArcaModels\DocCli;
use knet\ArcaModels\DocRow;
use knet\Helpers\FiltersUtils\ArrayFilter;
use knet\Helpers\FiltersUtils\BoolFilter;
use knet\Helpers\FiltersUtils\DateFilter;
use knet\Helpers\FiltersUtils\NumFilter;
use knet\Helpers\FiltersUtils\StringFilter;

class DocFilters
{
    private $filters = [];
    private $arrayFilterAvailable = [
        'tipodoc',
        'tipomodulo',
        'esercizio',
        'agente',
        'codicecf',
        'prGroupIncl',
        'prGroupExcl',
        'codArtIncl',
        'codArtExcl',
    ];
    private $dateFilterAvailable = [
        'datadoc',
        'dataconseg',
        'u_dtpronto',
    ];
    private $stringFilterAvailable = [
        'ragsoc',
        'codicearti',
        'gruppo',
    ];
    private $numFilterAvailable = [
        'quantita',
        'quantitare',
    ];
    private $boolFilterAvailable = [
        'evaso',
        'u_artlis',
        'u_perslis',
        'u_artlis&u_perslis',
        'ommerce',
        'filiali',
    ];
    private $filterLabel = [
        'tipodoc' => 'Tipo Doc.',
        'tipomodulo' => 'Tipologia Documento',
        'esercizio' => 'Esercizio',
        'agente' => 'Agente',
        'codicecf' => 'Codice Cliente',
        'ragsoc' => 'Rag.Sociale Cliente',
        'datadoc' => 'Data Doc.',
        'dataconseg' => 'Data Consegna',
        'u_dtpronto' => 'Data Pronto Merce',
        'evaso' => 'Evaso',
        'prGroupIncl' => 'Gruppi Prodotto Inclusi',
        'prGroupExcl' => 'Gruppi Prodotto Esclusi',
        'codArtIncl' => 'Codice Articolo Inclusi',
        'codArtExcl' => 'Codice Articolo Esclusi',
        'u_artlis' => 'Articoli a Listino',
        'u_perslis' => 'Articoli personalizzati Cliente',
        'u_artlis&u_perslis' => 'Articoli a Listino + Pers.Cli.',
        'ommerce' => 'Omaggi',
        'filiali' => 'Incluse Filiali',
        'quantita' => 'Quantità',
        'quantitare' => 'Quantità Residua',
        'codicearti' => 'Codice Articolo',
    ];

    public function addArrayFilter($label, $value) {
        if (in_array($label, $this->arrayFilterAvailable)){
            // if (array_key_exists($label, $this->filters)) {
                $this->filters[$label] = new ArrayFilter();
            if (gettype($value)=='array') {
                $this->filters[$label]->set($value);
            } else {
                $this->filters[$label]->set([$value]);
            }
            return $this->filters[$label]->isEnabled();
        }
        return false;
    }

    public function addDateFilter($label, $op, $date1, $date2=null)
    {
        if (in_array($label, $this->dateFilterAvailable)) {
            $this->filters[$label] = new DateFilter();
            $this->filters[$label]->set($op, $date1, $date2);
            return $this->filters[$label]->isEnabled();
        }
        return false;
    }

    public function addStringFilter($label, $op, $value)
    {
        if (in_array($label, $this->stringFilterAvailable)) {
            $this->filters[$label] = new StringFilter();
            $this->filters[$label]->set($op, $value);
            return $this->filters[$label]->isEnabled();
        }
        return false;
    }

    public function addNumFilter($label, $op, $value)
    {
        if (in_array($label, $this->numFilterAvailable)) {
            $this->filters[$label] = new NumFilter();
            $this->filters[$label]->set($op, $value);
            return $this->filters[$label]->isEnabled();
        }
        return false;
    }

    public function addBoolFilter($label, $value)
    {
        if (in_array($label, $this->boolFilterAvailable)) {
            $this->filters[$label] = new BoolFilter();
            $this->filters[$label]->set($value);
            return $this->filters[$label]->isEnabled();
        }
        return false;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getLabel($label)
    {
        return $this->filterLabel[$label];
    }

    public function queryBuilder($query) {
        // dd($query);
        // dd($query->getModel() instanceof DocCli);
        // dd($query->getModel() instanceof DocRow);
        if ($query->getModel() instanceof DocCli) {
            // dd($this->filters);
            foreach ($this->filters as $key => $filter) {
                if ($filter->isEnabled()){
                    switch ($key) {
                        case 'tipodoc':
                            $query->whereIn('tipodoc', $filter->get());
                            break;
                        case 'tipomodulo':
                            $query->whereIn('tipomodulo', $filter->get());
                            break;
                        case 'esercizio':
                            $query->whereIn('esercizio', $filter->get());
                            break;
                        case 'agente':
                            $query->whereIn('agente', $filter->get());
                            break;
                        case 'codicecf':
                            $query->whereIn('codicecf', $filter->get());
                            break;
                        case 'filiali':
                            if (!$filter->get() && RedisUser::get('ditta_DB') == 'knet_it') {
                                $query->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
                            }
                            break;
                        case 'datadoc':
                            if ($filter->getOp()=='between'){
                                $query->whereBetween('datadoc', $filter->get());
                            } else {
                                $query->where('datadoc', $filter->getOp(), $filter->get());
                            }
                            break; 
                        case 'u_artlis':
                            
                            $query->whereHas('docrow',function ($docRow) use ($filter) {
                                $docRow->whereHas('product', function ($q) use ($filter) {
                                    $q->where('u_artlis', $filter->get());
                                });
                            });
                            break;
                        case 'u_perslis':
                            $query->whereHas('docrow',function ($docRow) use ($filter) {
                                $docRow->whereHas('product', function ($q) use ($filter) {
                                    $q->where('u_perscli', $filter->get());
                                });
                            });
                            break;
                        case 'u_artlis&u_perslis':
                            
                            $query->whereHas('docrow',function ($docRow) use ($filter) {
                                $docRow->whereHas('product', function($q) use ($filter) {
                                    $q->orWhere('u_artlis', $filter->get())->orWhere('u_perscli', $filter->get());
                                });
                            });
                            break;
                        case 'ommerce':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('ommerce', $filter->get());
                            });
                            break;
                        case 'quantita':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('quantita', $filter->getOp(), $filter->get());
                            });
                            break;
                        case 'evaso':
                            if($filter->get()){
                                $query->whereHas('docrow', function ($docRow) use ($filter) {
                                    $docRow->where('quantitare', '=', 0);
                                });
                            } else {
                                $query->whereHas('docrow', function ($docRow) use ($filter) {
                                    $docRow->where('quantitare', '>', 0);
                                });
                            }
                            break;
                        case 'quantitare':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('quantitare', $filter->getOp(), $filter->get());
                            });
                            break;
                        case 'codicearti':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('codicearti', $filter->getOp(), $filter->get());
                            });
                            break;
                        case 'prGroupIncl':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where(function ($q) use ($filter) {
                                    $grupIn = $filter->get();
                                    $q->where('gruppo', 'like', $grupIn[0] . '%');
                                    if (count($grupIn) > 1) {
                                        for ($i = 1; $i < count($grupIn); $i++) {
                                            $q->orWhere('gruppo', 'like', $grupIn[$i] . '%');
                                        }
                                    }
                                });
                            });
                            break;
                        case 'prGroupExcl':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where(function ($q) use ($filter) {
                                    $grupIn = $filter->get();
                                    $q->where('gruppo', 'NOT LIKE', $grupIn[0] . '%');
                                    if (count($grupIn) > 1) {
                                        for ($i = 1; $i < count($grupIn); $i++) {
                                            $q->orWhere('gruppo', 'NOT LIKE', $grupIn[$i] . '%');
                                        }
                                    }
                                });
                            });
                            break;
                        case 'codArtIncl':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->whereIn('codicearti', $filter->get());
                            });
                            break;
                        case 'codArtExcl':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->whereNotIn('codicearti', $filter->get());
                            });
                            break;
                        case 'dataconseg':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('dataconseg', $filter->getOp(), $filter->get());
                            });
                            break;
                        case 'u_dtpronto':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('u_dtpronto', $filter->getOp(), $filter->get());
                            });
                            break;
                        case 'ragsoc':
                            $query->whereHas('client', function ($docRow) use ($filter) {
                                $docRow->where('descrizion', $filter->getOp(), $filter->get());
                            });
                            break;
                        default:
                            break;
                    }
                } else {

                    // 
                }
            }
        }
        if ($query->getModel() instanceof DocRow) {
            // dd($this->filters);
            foreach ($this->filters as $key => $filter) {
                if ($filter->isEnabled()){
                    switch ($key) {
                        case 'tipodoc':
                            $query->whereHas('doccli', function ($docCli) use ($filter) {
                                $docCli->whereIn('tipodoc', $filter->get());
                            });
                            // $query->whereIn('tipodoc', $filter->get());
                            break;
                        case 'tipomodulo':
                            $query->whereHas('doccli', function ($docCli) use ($filter) {
                                $docCli->whereIn('tipomodulo', $filter->get());
                            });
                            break;
                        case 'esercizio':
                            $query->whereHas('doccli', function ($docCli) use ($filter) {
                                $docCli->whereIn('esercizio', $filter->get());
                            });
                            // $query->whereIn('esercizio', $filter->get());
                            break;
                        case 'agente':
                            $query->whereHas('doccli', function ($docCli) use ($filter) {
                                $docCli->whereIn('agente', $filter->get());
                            });
                            break;
                        case 'codicecf':
                            $query->whereHas('doccli', function ($docCli) use ($filter) {
                                $docCli->whereIn('codicecf', $filter->get());
                            });
                            // $query->whereIn('codicecf', $filter->get());
                            break;
                        case 'filiali':
                            if (!$filter->get() && RedisUser::get('ditta_DB') == 'knet_it') {
                                $query->whereHas('doccli', function ($docCli) use ($filter) {
                                    $docCli->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
                                });
                                // $query->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
                            }
                            break;
                        case 'datadoc':
                            if ($filter->getOp()=='between'){
                                $query->whereHas('doccli', function ($docCli) use ($filter) {
                                    $docCli->whereBetween('datadoc', $filter->get());
                                });
                                // $query->whereBetween('datadoc', $filter->get());
                            } else {
                                $query->whereHas('doccli', function ($docCli) use ($filter) {
                                    $docCli->where('datadoc', $filter->getOp(), $filter->get());
                                });
                                // $query->where('datadoc', $filter->getOp(), $filter->get());
                            }
                            break; 
                        case 'u_artlis':
                            
                            $query->whereHas('product', function ($q) use ($filter) {
                                $q->where('u_artlis', $filter->get());
                            });
                            break;
                        case 'u_perslis':
                            $query->whereHas('product', function ($q) use ($filter) {
                                $q->where('u_perscli', $filter->get());
                            });
                            break;
                        case 'u_artlis&u_perslis':
                            // 
                            $query->whereHas('product', function($q) use ($filter) {
                                $q->orWhere('u_artlis', $filter->get())->orWhere('u_perscli', $filter->get());
                            });
                            break;
                        case 'ommerce':
                            $query->where('ommerce', $filter->get());
                            break;
                        case 'quantita':
                            $query->where('quantita', $filter->getOp(), $filter->get());
                            break;
                        case 'evaso':
                            if($filter->get()){
                                $query->where('quantitare', '=', 0);
                            } else {
                                $query->where('quantitare', '>', 0);
                            }
                            break;
                        case 'quantitare':
                            $query->where('quantitare', $filter->getOp(), $filter->get());
                            break;
                        case 'codicearti':
                            $query->where('codicearti', $filter->getOp(), $filter->get());
                            break;
                        case 'prGroupIncl':
                            $grupIn = $filter->get();
                            if (!empty($grupIn)) {
                                $query->where(function ($q) use ($grupIn) {
                                    $q->where('gruppo', 'like', $grupIn[0] . '%');
                                    if (count($grupIn) > 1) {
                                        for ($i = 1; $i < count($grupIn); $i++) {
                                            $q->orWhere('gruppo', 'like', $grupIn[$i] . '%');
                                        }
                                    }
                                });
                            }
                            break;
                        case 'prGroupExcl':
                            $grupNotIn = $filter->get();
                            if (!empty($grupNotIn)) {
                                $query->where(function ($q) use ($grupNotIn) {
                                    $q->where('gruppo', 'NOT LIKE', $grupNotIn[0] . '%');
                                    if (count($grupNotIn) > 1) {
                                        for ($i = 1; $i < count($grupNotIn); $i++) {
                                            $q->where('gruppo', 'NOT LIKE', $grupNotIn[$i] . '%');
                                        }
                                    }
                                });
                            }
                            break;
                        case 'codArtIncl':
                            $query->whereIn('codicearti', $filter->get());
                            break;
                        case 'codArtExcl':
                            $query->whereNotIn('codicearti', $filter->get());
                            break;
                        case 'dataconseg':
                            if ($filter->getOp() == 'between') {
                                $query->whereBetween('dataconseg', $filter->get());
                            } else {
                                $query->where('dataconseg', $filter->getOp(), $filter->get());
                            }
                            break;
                        case 'u_dtpronto':
                            if ($filter->getOp() == 'between') {
                                $query->whereBetween('u_dtpronto', $filter->get());
                            } else {
                                $query->where('u_dtpronto', $filter->getOp(), $filter->get());
                            }
                            // $query->where('u_dtpronto', $filter->getOp(), $filter->get());
                            break;
                        case 'ragsoc':
                            $query->whereHas('doccli', function ($docCli) use ($filter) {
                                $docCli->whereHas('client', function ($client) use ($filter) {
                                    $client->where('descrizion', $filter->getOp(), $filter->get());
                                });
                            });
                            break;
                        default:
                            break;
                    }
                } else {

                    // 
                }
            }
        }
        // dd($query->getModel());
        return $query;
    }

    public function queryCollection($query)
    {
        // dd($query);
        // dd($query->getModel() instanceof DocCli);
        // dd($query->getModel() instanceof DocRow);
        if ($query->first() instanceof DocCli) {
            // dd($this->filters);
            foreach ($this->filters as $key => $filter) {
                if ($filter->isEnabled()) {
                    switch ($key) {
                        case 'tipodoc':
                            $query->whereIn('tipodoc', $filter->get());
                            break;
                        case 'tipomodulo':
                            $query->whereIn('tipomodulo', $filter->get());
                            break;
                        case 'esercizio':
                            $query->whereIn('esercizio', $filter->get());
                            break;
                        case 'agente':
                            $query->whereIn('agente', $filter->get());
                            break;
                        case 'codicecf':
                            $query->whereIn('codicecf', $filter->get());
                            break;
                        case 'filiali':
                            if (!$filter->get() && RedisUser::get('ditta_DB') == 'knet_it') {
                                $query->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
                            }
                            break;
                        case 'datadoc':
                            if ($filter->getOp() == 'between') {
                                $query->whereBetween('datadoc', $filter->get());
                            } else {
                                $query->where('datadoc', $filter->getOp(), $filter->get());
                            }
                            break;
                        case 'u_artlis':

                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->whereHas('product', function ($q) use ($filter) {
                                    $q->where('u_artlis', $filter->get());
                                });
                            });
                            break;
                        case 'u_perslis':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->whereHas('product', function ($q) use ($filter) {
                                    $q->where('u_perscli', $filter->get());
                                });
                            });
                            break;
                        case 'u_artlis&u_perslis':

                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->whereHas('product', function ($q) use ($filter) {
                                    $q->orWhere('u_artlis', $filter->get())->orWhere('u_perscli', $filter->get());
                                });
                            });
                            break;
                        case 'ommerce':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('ommerce', $filter->get());
                            });
                            break;
                        case 'quantita':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('quantita', $filter->getOp(), $filter->get());
                            });
                            break;
                        case 'evaso':
                            if ($filter->get()) {
                                $query->whereHas('docrow', function ($docRow) use ($filter) {
                                    $docRow->where('quantitare', '=', 0);
                                });
                            } else {
                                $query->whereHas('docrow', function ($docRow) use ($filter) {
                                    $docRow->where('quantitare', '>', 0);
                                });
                            }
                            break;
                        case 'quantitare':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('quantitare', $filter->getOp(), $filter->get());
                            });
                            break;
                        case 'codicearti':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('codicearti', $filter->getOp(), $filter->get());
                            });
                            break;
                        case 'prGroupIncl':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where(function ($q) use ($filter) {
                                    $grupIn = $filter->get();
                                    $q->where('gruppo', 'like', $grupIn[0] . '%');
                                    if (count($grupIn) > 1) {
                                        for ($i = 1; $i < count($grupIn); $i++) {
                                            $q->orWhere('gruppo', 'like', $grupIn[$i] . '%');
                                        }
                                    }
                                });
                            });
                            break;
                        case 'prGroupExcl':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where(function ($q) use ($filter) {
                                    $grupIn = $filter->get();
                                    $q->where('gruppo', 'NOT LIKE', $grupIn[0] . '%');
                                    if (count($grupIn) > 1) {
                                        for ($i = 1; $i < count($grupIn); $i++) {
                                            $q->orWhere('gruppo', 'NOT LIKE', $grupIn[$i] . '%');
                                        }
                                    }
                                });
                            });
                            break;
                        case 'codArtIncl':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->whereIn('codicearti', $filter->get());
                            });
                            break;
                        case 'codArtExcl':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->whereNotIn('codicearti', $filter->get());
                            });
                            break;
                        case 'dataconseg':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('dataconseg', $filter->getOp(), $filter->get());
                            });
                            break;
                        case 'u_dtpronto':
                            $query->whereHas('docrow', function ($docRow) use ($filter) {
                                $docRow->where('u_dtpronto', $filter->getOp(), $filter->get());
                            });
                            break;
                        case 'ragsoc':
                            $query->whereHas('client', function ($docRow) use ($filter) {
                                $docRow->where('descrizion', $filter->getOp(), $filter->get());
                            });
                            break;
                        default:
                            break;
                    }
                } else {

                    // 
                }
            }
        }
        if ($query->first() instanceof DocRow) {
            // dd($this->filters);
            foreach ($this->filters as $key => $filter) {
                if ($filter->isEnabled()) {
                    switch ($key) {
                        case 'tipodoc':
                            $query->filter(fn($item) => $item->doccli->whereIn('tipodoc', $filter->get()));
                            // $query->whereHas('doccli', function ($docCli) use ($filter) {
                            //     $docCli->whereIn('tipodoc', $filter->get());
                            // });
                            // $query->whereIn('tipodoc', $filter->get());
                            break;
                        case 'tipomodulo':
                            $query->whereHas('doccli', function ($docCli) use ($filter) {
                                $docCli->whereIn('tipomodulo', $filter->get());
                            });
                            break;
                        case 'esercizio':
                            $query->whereHas('doccli', function ($docCli) use ($filter) {
                                $docCli->whereIn('esercizio', $filter->get());
                            });
                            // $query->whereIn('esercizio', $filter->get());
                            break;
                        case 'agente':
                            $query->filter(fn($item) => $item->doccli->whereIn('agente', $filter->get()));
                            // $query->whereHas('doccli', function ($docCli) use ($filter) {
                            //     $docCli->whereIn('agente', $filter->get());
                            // });
                            break;
                        case 'codicecf':
                            $query->whereHas('doccli', function ($docCli) use ($filter) {
                                $docCli->whereIn('codicecf', $filter->get());
                            });
                            // $query->whereIn('codicecf', $filter->get());
                            break;
                        case 'filiali':
                            if (!$filter->get() && RedisUser::get('ditta_DB') == 'knet_it') {
                                $query->whereHas('doccli', function ($docCli) use ($filter) {
                                    $docCli->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
                                });
                                // $query->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
                            }
                            break;
                        case 'datadoc':
                            if ($filter->getOp() == 'between') {
                                $query->whereHas('doccli', function ($docCli) use ($filter) {
                                    $docCli->whereBetween('datadoc', $filter->get());
                                });
                                // $query->whereBetween('datadoc', $filter->get());
                            } else {
                                $query->whereHas('doccli', function ($docCli) use ($filter) {
                                    $docCli->where('datadoc', $filter->getOp(), $filter->get());
                                });
                                // $query->where('datadoc', $filter->getOp(), $filter->get());
                            }
                            break;
                        case 'u_artlis':

                            $query->whereHas('product', function ($q) use ($filter) {
                                $q->where('u_artlis', $filter->get());
                            });
                            break;
                        case 'u_perslis':
                            $query->whereHas('product', function ($q) use ($filter) {
                                $q->where('u_perscli', $filter->get());
                            });
                            break;
                        case 'u_artlis&u_perslis':
                            $query->filter(fn($item) => (($item->product->u_artlis ?? false == $filter->get()) || ($item->product->u_perscli ?? false == $filter->get())));
                            // $query->whereHas('product', function ($q) use ($filter) {
                            //     $q->orWhere('u_artlis', $filter->get())->orWhere('u_perscli', $filter->get());
                            // });
                            break;
                        case 'ommerce':
                            $query->where('ommerce', $filter->get());
                            break;
                        case 'quantita':
                            $query->where('quantita', $filter->getOp(), $filter->get());
                            break;
                        case 'evaso':
                            if ($filter->get()) {
                                $query->where('quantitare', '=', 0);
                            } else {
                                $query->where('quantitare', '>', 0);
                            }
                            break;
                        case 'quantitare':
                            $query->where('quantitare', $filter->getOp(), $filter->get());
                            break;
                        case 'codicearti':
                            $query->where('codicearti', $filter->getOp(), $filter->get());
                            break;
                        case 'prGroupIncl':
                            $grupIn = $filter->get();
                            if (!empty($grupIn)) {
                                $query->where(function ($q) use ($grupIn) {
                                    $q->where('gruppo', 'like', $grupIn[0] . '%');
                                    if (count($grupIn) > 1) {
                                        for ($i = 1; $i < count($grupIn); $i++) {
                                            $q->orWhere('gruppo', 'like', $grupIn[$i] . '%');
                                        }
                                    }
                                });
                            }
                            break;
                        case 'prGroupExcl':
                            $grupNotIn = $filter->get();
                            if (!empty($grupNotIn)) {
                                $query->where(function ($q) use ($grupNotIn) {
                                    $q->where('gruppo', 'NOT LIKE', $grupNotIn[0] . '%');
                                    if (count($grupNotIn) > 1) {
                                        for ($i = 1; $i < count($grupNotIn); $i++) {
                                            $q->where('gruppo', 'NOT LIKE', $grupNotIn[$i] . '%');
                                        }
                                    }
                                });
                            }
                            break;
                        case 'codArtIncl':
                            $query->whereIn('codicearti', $filter->get());
                            break;
                        case 'codArtExcl':
                            $query->whereNotIn('codicearti', $filter->get());
                            break;
                        case 'dataconseg':
                            if ($filter->getOp() == 'between') {
                                $query->whereBetween('dataconseg', $filter->get());
                            } else {
                                $query->where('dataconseg', $filter->getOp(), $filter->get());
                            }
                            break;
                        case 'u_dtpronto':
                            if ($filter->getOp() == 'between') {
                                $query->whereBetween('u_dtpronto', $filter->get());
                            } else {
                                $query->where('u_dtpronto', $filter->getOp(), $filter->get());
                            }
                            // $query->where('u_dtpronto', $filter->getOp(), $filter->get());
                            break;
                        case 'ragsoc':
                            $query->whereHas('doccli', function ($docCli) use ($filter) {
                                $docCli->whereHas('client', function ($client) use ($filter) {
                                    $client->where('descrizion', $filter->getOp(), $filter->get());
                                });
                            });
                            break;
                        default:
                            break;
                    }
                } else {

                    // 
                }
            }
        }
        // dd($query->getModel());
        return $query;
    }

    public function toString() {
        $result = '';
        foreach ($this->filters as $key => $filter) {
            if ($filter->isEnabled()) {
                $result .= $this->getLabel($key).': '.$filter->toString() .'; ';
            }
        }
        return $result;
    }
}