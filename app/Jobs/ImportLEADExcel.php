<?php

namespace knet\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use knet\WebModels\wRubrica;
use knet\WebModels\wVisit;
use RedisUser;
use ZipCode;
use Carbon;
use Excel;
use Log;

class ImportLEADExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $file = '';
    protected $country = '';
    protected $mese = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file, $country, $mese)
    {
        $this->file = $file;
        $this->country = $country;
        $this->mese = $mese;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $destinationPath = public_path()."/upload/LEAD/";
        $file = $this->file;
        Log::info("INIZIO IMPORTAZIONE UTENTI da file: ".$this->file);
        $rows = Excel::load($destinationPath."/".$file, function($reader) {})->all();
        Log::info("Numero di Righe: ".count($rows));
        
        //Setto il Country nel ZipCode
        ZipCode::setCountry($this->country);
      
        foreach ($rows as $row) {
            Log::info($row->RagioneSociale);

            if(!empty($row->email) && strpos($row->email,"@")>0){
                $contatto = wRubrica::where("email", $row->email)->first();
                if($contatto==null){ // Creo l'Utente

                    // Cerco gli indirizzi
                    // Prima di tutto cerco tramite CAP
                    if(!empty($row->cap)){
                        $cap = str_pad($row->cap, ZipCode::getZipLength(), "0", STR_PAD_LEFT);
                        $result = ZipCode::find($cap);
                        if($result->getSuccess() && $result->getWebService()=="Geonames"){ 
                            if(substr(strtolower(($result->getAddresses())[0]['city']),0,4)==substr(strtolower($row->localita),0,4)){
                                $localita = ($result->getAddresses())[0]['city'].", ".($result->getAddresses())[0]['department'];
                            } else {
                                $localita = ucfirst($row->localita).", ".($result->getAddresses())[0]['department'];
                            }
                            $prov = ($result->getAddresses())[0]['department_id'];
                            $regione = ($result->getAddresses())[0]['state_name'];
                        } else {
                            $localita = $row->localita;
                            $prov = $row->prov;
                            $regione = '';
                        }
                    } else {
                        $localita = $row->localita;
                        $prov = $row->prov;
                        $cap = $row->cap;
                        $regione = '';
                    }
                    $codnazione = 'IT';
                    $contatto = wRubrica::create([
                        'descrizion'  => $row->RagioneSociale,
                        'telefono' => $row->Telefono,
                        'sitoweb' => $row->Sito,
                        'settore' => $row->Settore,
                        'persdacont' => $row->NomeContatto,
                        'email' => $row->email,
                        'localita' => $localita,
                        'prov' => $prov,
                        'cap' => $cap,
                        'regione' => $regione,
                        'codnazione' => $codnazione,
                    ]);

                    $contatto->save();
                }

                if(!empty($row->feedback)){
                    $visit = wVisit::create([
                        'rubri_id' => $contatto->id,
                        'user_id' => Auth::user()->id,
                        'data' => new Carbon('last day of '.Carbon::createFromDate(null, $this->mese, null)->format('F').' '.((string)Carbon::now()->year)),
                        'tipo' => 'MEET',
                        'descrizione' => 'First LEAD FeedBack',
                        'note' => $row->feedback
                    ]);
                    $visit->save();
                }
            } 
        }
        Log::info('FINE PROCEDURA IMPORT LEAD!');
    }
}
