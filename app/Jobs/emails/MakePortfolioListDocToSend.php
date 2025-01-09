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

class MakePortfolioListDocToSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $thisYear;
    private $prevYear;
    private $dStartMonth;
    private $dEndMonth;
    private $cumulativo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $cumulativo = false)
    {
        $this->user = $user;
        $this->cumulativo = $cumulativo;
        Log::info('MakePortfolioListDocToSend Job Created');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('SendDocListByEmail Job Started');

        Auth::loginUsingId($this->user->id);
        RedisUser::store();

        // $attachReport = $this->createReport();
    

        try {
            Log::info('Invio Report Portfolio List Doc - ' . $this->user->name);
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

        Log::info('SendDocListByEmail Job Ended');
    }
    protected function createReport()
    {
        // Costruisco i filtri
        $this->thisYear = Carbon::now()->year;
        $this->prevYear = $this->thisYear - 1;
        $this->dStartMonth = new Carbon('first day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
        $this->dEndMonth = new Carbon('last day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
        $mese = Carbon::now()->month;
        $cumulativo = $this->cumulativo;
        if ($cumulativo) {
            $this->dStartMonth = new Carbon('first day of january ' . ((string)$this->thisYear));
        }

        $agents = Agent::select('codice', 'descrizion')->whereNull('u_dataini')->orWhere('u_dataini', '>=', Carbon::now())->orderBy('codice')->get();
        $fltAgents = $agents->pluck('codice')->toArray(); //$agents->pluck('codice')->toArray();
        $fltAgents = AgentFltUtils::checkSpecialRules($fltAgents);

        $listOC = $this->getListDoc(['OC'], $fltAgents)->groupBy('agente');
        $listXC = $this->getListDoc(['XC'], $fltAgents)->groupBy('agente');

        $title = "Portafoglio";
        $subTitle = "Dettaglio Documenti". "_" .  $this->user->name;
        $filename = "Report_" . $title . "_" . $subTitle;
        $view = '_exports.pdf.portfolioDocPdf';
        $data = [
            'agents' => $agents,
            'mese' => $mese,
            'cumulativo' => $cumulativo,
            'thisYear' => $this->thisYear,
            'prevYear' => $this->prevYear,
            'fltAgents' => $fltAgents,
            'listOC' => $listOC,
            'listXC' => $listXC,
        ];
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);
        if (Storage::exists('ReportPDFToSend/' . $filename . '.pdf')) {
            Storage::delete('ReportPDFToSend/' . $filename . '.pdf');
        }
        $pdf->save(storage_path('app') . '/' . 'ReportPDFToSend/' . $filename . '.pdf');
        return storage_path('app') . '/' . 'ReportPDFToSend/' . $filename . '.pdf';
    }

    // LISTA DEI DOCUMENTI
    public function getListDoc($tipodocs, $agents = [], $evasi = false, $filiali = false)
    {
        $docTes = DocCli::whereIn('tipodoc', $tipodocs);
        if (!$evasi) {
            $docTes->whereHas('docrow', function ($query) {
                $query->where('quantitare', '>', 0)
                ->where('ommerce', 0)
                ->where('codicearti', '!=',
                    ''
                )
                ->whereHas('product', function ($q) {
                    $q->orWhere('u_artlis', 1)->orWhere('u_perscli', 1);
                });
            });
        }
        if (in_array("OC", $tipodocs) || in_array("XC", $tipodocs)
        ) {
            $docTes->whereHas('docrow', function ($query) {
                $query->where('dataconseg', '<=', $this->dEndMonth);;
            })
            ->whereIn('esercizio', [(string)$this->thisYear, (string)$this->prevYear]);
        } else {
            $docTes->whereBetween('datadoc', [$this->dStartMonth, $this->dEndMonth]);
        }
        if (!$filiali && RedisUser::get('ditta_DB') == 'knet_it') {
            $docTes->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
        }
        if (!empty($agents)) {
            $docTes->whereIn('agente', $agents);
        }
        $docTes = $docTes->with(['client', 'agent'])->orderBy('codicecf')->orderBy('datadoc', 'desc')->get();

        return $docTes;
    }
}
