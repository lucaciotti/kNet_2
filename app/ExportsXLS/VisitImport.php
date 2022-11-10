<?php

namespace knet\ExportsXLS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;

use knet\WebModels\wRubrica;
use knet\WebModels\wVisit;
use knet\ArcaModels\Agent;
use knet\ArcaModels\Client;
use knet\User;
use RedisUser;
use Carbon\Carbon;
use Log;
use Auth;

/* 
use knet\ArcaModels\DocCli;
use knet\ArcaModels\Destinaz;
use knet\ArcaModels\DocRow; 
implements WithMapping, ToCollection*/

class VisitImport
{
    use Importable;

    protected $titleRow;
    protected $originalCollect;
    protected $end = false;

    public function __construct($fileName) {
        ini_set('max_execution_time', 180);
        $collect = $this->toCollection("/upload/Visit/".$fileName);
        $this->originalCollect = $collect;
        $this->postProcess();
    }

    public function postProcess(){
        $collect = $this->originalCollect->first();
        $firstRow = $collect->first();
        $collect = $collect->forget($collect->keys()->first());
        $this->titleRow = $firstRow;
        $combineRow = collect();
        foreach ($collect as $row) {
            $combine = $firstRow->combine($row);
            $combineRow->push($combine);
        }
        $filtered = $combineRow->reject(function ($value, $key) {
            return $value->first() === null;
        });
        // dd($filtered->first()->keys());
        $this->letImportToDB($filtered);
    }

    public function letImportToDB($rows){
        $channel = 'insertVisit';
        Log::channel($channel)->info("------------------------");
        Log::channel($channel)->info("INIZIO IMPORTAZIONE VISITE AGENTI");
        
        $total_rows = count($rows);
        $n_row = 0;
        $visitToInsert = [];
        foreach ($rows as $row) {
            $n_row++;

            $email_user = $row['Nome utente'];
            $agente = $row['ÁREA MANAGER'];
            $visit_data = new Carbon($row['FECHA']);
            $visit_tipoCliente = $row['CLIENTE']=='ACTUAL' ? 'client' : 'rubri';
            $rubri_settore = $row['SEGMENTO CLIENTE'];
            $visit_ragsoc = strtoupper($row['RAZÓN SOCIAL CLIENTE']);
            $rubri_location = $row['PROVINCIA'].' - '. $row['POBLACIÓN'];
            $visit_persona = $row['PERSONA DE CONTACTO'];
            $visit_posPersona = $row['FUNCIÓN PERSONA DE CONTACTO'];
            $visit_desc = $row['MOTIVO DE LA VISITA'];
            $visit_note = $row['DESARROLLO DE LA VISITA (Síntesis)'];
            $visit_concl = $row['CONCLUSIONES'];
            $visit_ord = $row['SE HACE PRESUPUESTO']=='Sí' ? True : False;
            
            // 1 Cerco l'utente
            $user_id = 0;
            $codag = '';
            $user = User::where('name', 'like', '%'.$agente.'%')->first();
            if($user){
                $user_id = $user->id;
                $codag = $user->codag;
            } else {
                Log::channel($channel)->error($n_row.': '.$visit_ragsoc.' ['.$visit_tipoCliente.'] : NON TROVATO UTENTE');
                continue;                
            }

            // 2 Cerco cliente/contatto (oppure creo contatto)
            $codicecf = null;
            $rubri_id = null;
            if($visit_tipoCliente=='client'){
                $client = Client::select('codice', 'descrizion')->where('descrizion', 'like', '%'.$visit_ragsoc.'%')->first();
                if ($client){
                    $codicecf = $client->codice;
                    Log::channel($channel)->notice($n_row.': '.$visit_ragsoc.' ['.$visit_tipoCliente.'] : CLIENTE ASSOCIATO : '. $client->descrizion);
                } else {
                    $trovato=false;
                    foreach(explode(" ",$visit_ragsoc) as $str) {
                        $test_client = Client::select('codice', 'descrizion')->where('descrizion', 'like', '%'.$str.'%')->get();
                        if(count($test_client)==1){
                            // $clients += [ count($test_client) => $test_client->first()->codice':'$test_client->first()->descrizion ];
                            $codicecf = $test_client->first()->codice;
                            Log::channel($channel)->notice($n_row.': '.$visit_ragsoc.' ['.$visit_tipoCliente.'] : CLIENTE ASSOCIATO : '. $test_client->first()->descrizion);
                            break;
                        }
                    }
                    if (!$trovato){
                        Log::channel($channel)->error($n_row.': '.$visit_ragsoc.' ['.$visit_tipoCliente.'] : NON TROVATO CLIENTE');
                        continue;
                    }
                }
            }
            if($visit_tipoCliente=='rubri'){
                $rubri = wRubrica::select('id', 'descrizion')->where('descrizion', 'like', '%'.$visit_ragsoc.'%')->first();
                if ($rubri){
                    $rubri_id = $rubri->id;
                } else {
                    $rubri = wRubrica::create([
                        'descrizion' => $visit_ragsoc,
                        'partiva' => '',
                        'user_id' => $user_id,
                        'settore' => $rubri_settore,
                        'statocf' => 'T',
                        'codnazione' => 'ES',
                        'localita' => $rubri_location,
                        'indirizzo' => '',
                        'cap' => '',
                        'email' => '',
                        'agente' => $codag,
                        'persdacont' => $visit_persona,
                        'posperscon' => $visit_posPersona,
                        'telefono' => '',
                        'sitoweb' => ''
                    ]);
                    $rubri_id = $rubri->id;
                }
            }

            // 3 Proseguiamo solo se Rubri_id e codicecf NON sono vuoti
            if (empty($rubri_id) and empty($codicecf)) {
                Log::channel($channel)->error($n_row.': '.$visit_ragsoc.' ['.$visit_tipoCliente.'] : NESSUN RIFERIMENTO CONTATTO O CLIENTE');
                continue;
            }

            // 4 Controlliamo che Visita non sia già presente in archivio
            $visit = wVisit::where('codicecf', $codicecf)
                            ->where('rubri_id', $rubri_id)
                            ->where('data', $visit_data)
                            ->where('user_id', $user_id)
                            ->first();
            if ($visit) {
                Log::channel($channel)->error($n_row.': '.$visit_ragsoc.' ['.$visit_tipoCliente.'] : VISITA GIA\' ARCHIVIATA');
                continue;
            }

            // POSSIAMO FINALMENTE INSERIRE LA VISITA
            array_push($visitToInsert, [
                'codicecf' => $codicecf,
                'rubri_id' => $rubri_id,
                'user_id' => $user_id,
                'data' => $visit_data,
                'tipo' => 'Meet',
                'descrizione' => $visit_desc ?? 'Alert',
                'note' => $visit_note,
                'conclusione' => $visit_concl ?? '',
                'persona_contatto' => $visit_persona ?? '',
                'funzione_contatto' => $visit_posPersona ?? '',
                'ordine' => $visit_ord
            ]);
            // $visit = wVisit::create([
            //     'codicecf' => $codicecf,
            //     'rubri_id' => $rubri_id,
            //     'user_id' => $user_id,
            //     'data' => $visit_data,
            //     'tipo' => 'Meet',
            //     'descrizione' => $visit_desc ?? 'Alert',
            //     'note' => $visit_note,
            //     'conclusione' => $visit_concl ?? '',
            //     'persona_contatto' => $visit_persona ?? '',
            //     'funzione_contatto' => $visit_posPersona ?? '',
            //     'ordine' => $visit_ord
            // ]);
            
            
            // Log::channel($channel)->info($n_row.': '.$visit_ragsoc.' ['.$visit_tipoCliente.'] : INSERITA CORRETTAMENTE');
            // Log::info(print_r($row, true));
            
        }
        // dd($visitToInsert);
        wVisit::insert($visitToInsert);
        Log::channel($channel)->info('Importate '.count($visitToInsert). ' Visite');
        $this->end = true;
        return true;
    }
    
    public function getResult(){
        return $this->end;
    }
}