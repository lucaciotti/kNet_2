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
use RedisUser;
use ZipCode;
use Carbon\Carbon;
use Log;
use Auth;

/* 
use knet\ArcaModels\DocCli;
use knet\ArcaModels\Destinaz;
use knet\ArcaModels\DocRow; 
implements WithMapping, ToCollection*/

class LEADImport
{
    use Importable;

    protected $titleRow;
    protected $originalCollect;
    protected $country;
    protected $mese;
    protected $end = false;

    public function __construct($fileName, $country = 'IT', $mese = 1) {
        $this->country = $country;
        $this->mese = $mese;
        $collect = $this->toCollection("/upload/LEAD/".$fileName);
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
        Log::info("INIZIO IMPORTAZIONE LEAD");
        ZipCode::setCountry($this->country);
      
        foreach ($rows as $row) {
            Log::info($row['RagioneSociale']);
            // Log::info(print_r($row, true));
            if(!empty($row['partitaiva'])){
                $contatto = wRubrica::where("partiva", $row['partitaiva'])->first();
                if($contatto==null){ // Creo il contatto
                    $contatto = wRubrica::firstOrCreate([
                        'partiva' => $row['partitaiva']
                    ],[
                        'descrizion'  => $row['RagioneSociale'],
                        'telefono' => $row['telefono'],
                        'settore' => 'FALEGNAMERIA',
                        'legalerapp' => $row['legaleRapp'],  
                        'indirizzo' => $row['Indirizzo'],                      
                        'localita' => $row['localita'],
                        'prov' => $row['prov'],
                        'cap' => $row['cap'],
                        'regione' => $row['regione'],
                        'codnazione' => $this->country,
                        'code_ateco' => $row['code_ateco'], 
                        'codfiscale' => $row['codicefiscale'],
                        'partiva' => $row['partitaiva'], 
                        'sitoweb' => $row['sitoweb'], 
                        'email' => $row['email'], 
                        'nDipendenti' => $row['nDipendenti'], 
                        'agente' => $row['agente'],
                        'user_id' => Auth::user()->id
                    ]);
                } else {
                    Log::info("Contatto già presente in Rubrica...");
                    Log::info(print_r($row, true));
                }
            }

            // OLD IMPORT LEAD
            /* if(!empty($row['email']) && strpos($row['email'],"@")>0){
                $contatto = wRubrica::where("email", $row['email'])->first();
                if($contatto==null){ // Creo il contatto
                    // Cerco gli indirizzi
                    // Prima di tutto cerco tramite CAP
                    if(!empty($row['CAP'])){
                        $cap = str_pad($row['CAP'], ZipCode::getZipLength(), "0", STR_PAD_LEFT);
                        $result = ZipCode::find($cap);
                        if($result->getSuccess() && $result->getWebService()=="Geonames"){ 
                            if(substr(strtolower(($result->getAddresses())[0]['city']),0,4)==substr(strtolower($row['Localita']),0,4)){
                                $localita = ($result->getAddresses())[0]['city'].", ".($result->getAddresses())[0]['department'];
                            } else {
                                $localita = ucfirst($row['Localita']).", ".($result->getAddresses())[0]['department'];
                            }
                            $prov = ($result->getAddresses())[0]['department_id'];
                            $regione = ($result->getAddresses())[0]['state_name'];
                        } else {
                            $localita = $row['Localita'];
                            $prov = $row['Prov'];
                            $regione = '';
                        }
                    } else {
                        $localita = $row['Localita'];
                        $prov = $row['Prov'];
                        $cap = $row['CAP'];
                        $regione = '';
                    }
                    $codnazione = 'IT';
                    $contatto = wRubrica::firstOrCreate([
                        'email' => $row['email']
                    ],[
                        'descrizion'  => $row['RagioneSociale'],
                        'telefono' => $row['Telefono'],
                        'sitoweb' => $row['Sito'],
                        'settore' => $row['Settore'],
                        'persdacont' => $row['NomeContatto'],  
                        'indirizzo' => $row['Indirizzo'],                      
                        'localita' => $localita,
                        'prov' => $prov,
                        'cap' => $cap,
                        'regione' => $regione,
                        'codnazione' => $codnazione,
                        'user_id' => Auth::user()->id
                    ]);

                }

                if(!empty($row['Feedback'])){
                    $visit = wVisit::create([
                        'rubri_id' => $contatto->id,
                        'user_id' => Auth::user()->id,
                        'data' => new Carbon('last day of '.Carbon::createFromDate(null, $this->mese, null)->format('F').' '.((string)Carbon::now()->year)),
                        'tipo' => 'Add_',
                        'descrizione' => 'Creato Contatto',
                        'note' => $row['Feedback']
                    ]);
                    //$visit->save();
                } 
            } */
        }
        Log::info('FINE PROCEDURA IMPORT LEAD!');
        $this->end = true;
        return true;
    }
    
    public function getResult(){
        return $this->end;
    }
}