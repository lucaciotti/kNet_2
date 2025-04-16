<?php

namespace knet\Jobs\emails;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Auth;
use knet\ArcaModels\Agent;
use knet\ArcaModels\DocCli;
use knet\Helpers\AgentFltUtils;
use knet\Helpers\PdfReport;
use knet\Helpers\RedisUser;
use knet\Mail\SendPortfolioListDoc;

class MakeStatFatByCustomerToSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $thisYear;
    private $prevYear;
    private $dStartMonth;
    private $dEndMonth;
    protected $arrayIDOC;
    protected $arrayIDBO;
    protected $arrayIDFT;
    protected $arrayIDprevFT;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $cumulativo = false)
    {
        $this->user = $user;
        Log::info('MakeStatFatByCustomerToSend Job Created');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('MakeStatFatByCustomerToSend Job Started');

        Auth::loginUsingId($this->user->id);
        RedisUser::store();

        // $attachReport = $this->createReport();
    

        try {
            Log::info('Invio Report StatFat Customer - ' . $this->user->name);
            // TODO
            // $isInvio = ((!$user->auto_email && $client->fat_email) || ($user->auto_email && $user->auto_email));
            $fileToAttach = $this->createReport();
            $mail = (new SendPortfolioListDoc($this->user, $fileToAttach))->onQueue('emails');
            if (App::environment(['local', 'staging'])) {
                $toEmail = 'luca.ciotti@gmail.com';
                Mail::to($toEmail)->queue($mail);
                // Mail::to($toEmail)->cc(['emanuela.prioli@k-group.com'])->queue($mail);
                Log::info('Invio Report Portfolio AgCustomer:' . $this->user->name . 'MailedJob to ' . $toEmail);
            } else {
                $toEmail = $this->user->email;
                Mail::to($toEmail)->cc(['emanuela.prioli@k-group.com'])->bcc(['luca.ciotti@gmail.com'])->queue($mail);
                Log::info('Invio Report Portfolio AgCustomer:' . $this->user->name . 'MailedJob to ' . $toEmail);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        Log::info('MakeStatFatByCustomerToSend Job Ended');
    }
    protected function createReport()
    {
        // Costruisco i filtri
        $this->thisYear = Carbon::now()->year;
        $thisYear = $this->thisYear;
        $this->dStartMonth = new Carbon('first day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
        $this->dEndMonth = new Carbon('last day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));

        $agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', Carbon::now())->orderBy('codice')->get();
        $fltAgents = $agents->pluck('codice')->toArray(); //$agents->pluck('codice')->toArray();
        $fltAgents = AgentFltUtils::checkSpecialRules($fltAgents);

        $yearBack = 2; // 2->3AnniView; 3->4AnniView; 4->5AnniView
        $limitVal = null;
        $meseSelected = Carbon::now()->month;
        $onlyMese = false;
        $isPariPeriodo = false;

        $querySelect_qta = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.qta_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.qta_tot';
        $querySelect_qtaN = ($isPariPeriodo ? $querySelect_qta : 'u_statfatt_art.qta_tot');
        $querySelect_fat = $meseSelected ? $this->buildQueryPeriodo('u_statfatt_art.val_', intval($meseSelected), $onlyMese) : 'u_statfatt_art.val_tot';
        $querySelect_fatN = ($isPariPeriodo ? $querySelect_fat : 'u_statfatt_art.val_tot');

        // Qui costruisco solo la tabella con il fatturato dei clienti
        $fatList = DB::connection(RedisUser::get('ditta_DB'))->table('u_statfatt_art')
        ->join('anagrafe', 'anagrafe.codice', '=', 'u_statfatt_art.codicecf')
        ->leftJoin('settori', 'settori.codice', '=', 'anagrafe.settore')
        ->leftjoin('magart', 'magart.codice', '=', 'u_statfatt_art.codicearti')
        ->leftJoin('maggrp', 'maggrp.codice', '=', 'u_statfatt_art.gruppo')
        ->leftjoin('maggrp as macrogrp', function ($join) {
            $join->on('macrogrp.codice', '=', 'u_statfatt_art.macrogrp')
            ->whereRaw('LENGTH(macrogrp.codice) = ?', [3]);
        })
        ->select('u_statfatt_art.codicearti', 'u_statfatt_art.codicecf')
        ->selectRaw('MAX(magart.descrizion) as descrArt')
        ->selectRaw('MAX(u_statfatt_art.macrogrp) as macrogrp')
        ->selectRaw('MAX(macrogrp.descrizion) as descrMacrogrp')
        ->selectRaw('MAX(u_statfatt_art.gruppo) as codGruppo')
        ->selectRaw('MAX(maggrp.descrizion) as descrGruppo')
        ->selectRaw('MAX(u_statfatt_art.prodotto) as tipoProd')
        ->selectRaw('MIN(u_statfatt_art.mese_parz) as meseRif')
        ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qta . ', 0)) as qtaN', [$thisYear])
        ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fat . ')/(' . $querySelect_qta . '), 0), 0)) as pmN', [$thisYear])
        ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fat . ', 0)) as fatN', [$thisYear])
        ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN1', [$thisYear - 1])
        ->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN1', [$thisYear - 1])
        ->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN1', [$thisYear - 1]);

        switch ($yearBack) {
            case 2:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN2', [$thisYear - 2]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN2', [$thisYear - 2]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN2', [$thisYear - 2]);
            case 3:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN2', [$thisYear - 2]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN2', [$thisYear - 2]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN2', [$thisYear - 2]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                break;
            case 4:
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN2', [$thisYear - 2]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0), 0)) as pmN2', [$thisYear - 2]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN2', [$thisYear - 2]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN3', [$thisYear - 3]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN3', [$thisYear - 3]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_qtaN . ', 0)) as qtaN4', [$thisYear - 4]);
                $fatList->selectRaw('MAX(IFNULL(IF(u_statfatt_art.esercizio = ?,(' . $querySelect_fatN . ')/(' . $querySelect_qtaN . '), 0),0)) as pmN4', [$thisYear - 4]);
                $fatList->selectRaw('SUM(IF(u_statfatt_art.esercizio = ?,' . $querySelect_fatN . ', 0)) as fatN4', [$thisYear - 4]);
                break;
        }
        $fatList->whereRaw('u_statfatt_art.esercizio >= ?', [$thisYear - $yearBack]);
        $fatList->whereIn('anagrafe.agente', $fltAgents);
        $fatList->whereRaw('(LEFT(u_statfatt_art.codicearti,4) != ? AND LEFT(u_statfatt_art.codicearti,4) != ?)', ['CAMP', 'NOTA']);
        $fatList->whereRaw('(LEFT(u_statfatt_art.gruppo,1) != ? AND LEFT(u_statfatt_art.gruppo,1) != ? AND LEFT(u_statfatt_art.gruppo,3) != ?)', ['C', '2', 'DIC']);

        $fatList->groupBy(['codicearti', 'codicecf']);
        if ($limitVal != null) {
            $fatList->havingRaw('fatN >= ?', [$limitVal]);
        }
        $fatList->orderBy('codGruppo')->orderBy('codicearti');
        $fatList = $fatList->get();

        $listCodicecf = $fatList->pluck('codicecf')->unique()->toArray();

        $fatList = $fatList->groupBy('codicecf');

        $customers = Client::whereIn('codice', $listCodicecf)->orderBy('codice')->get();
        // dd($listCodicecf);

        $meseRif = $meseSelected ? $meseSelected : ($fatList->first() ? $fatList->first()->meseRif : Carbon::now()->month);

        $title = "Scheda Confronto Anni";
        $subTitle = "NONE";
        $view = '_exports.pdf.schedaFatArtPdfByCustomer';
        $data = [
            'customers' => $customers,
            'fatListByCustomer' => $fatList,
            'thisYear' => $this->thisYear,
            'yearback' => $yearBack,
            'mese' => $meseRif,
            'onlyMese' => $onlyMese,
            'pariperiodo' => $isPariPeriodo
        ];
        
        $filename = "Report_" . $title . "_" . $subTitle;
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);
        if (Storage::exists('ReportPDFToSend/' . $filename . '.pdf')) {
            Storage::delete('ReportPDFToSend/' . $filename . '.pdf');
        }
        $pdf->save(storage_path('app') . '/' . 'ReportPDFToSend/' . $filename . '.pdf');
        return storage_path('app') . '/' . 'ReportPDFToSend/' . $filename . '.pdf';
    }

    private function buildQueryPeriodo($prefColumn, $mese, $onlyMese)
    {
        $q = '';
        if ($onlyMese) {
            $q = $prefColumn . str_pad(strval($mese), 2, "0", STR_PAD_LEFT);
        } else {
            for ($i = 1; $i <= $mese; $i++) {
                if (empty($q)) {
                    $q .= $prefColumn . str_pad(strval($i), 2, "0", STR_PAD_LEFT);
                } else {
                    $q .= '+' . $prefColumn . str_pad(strval($i), 2, "0", STR_PAD_LEFT);
                }
            }
        }
        return $q;
    }

    
}
