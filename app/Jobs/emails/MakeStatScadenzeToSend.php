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
use Illuminate\Support\Facades\DB;
use knet\ArcaModels\ScadCli;
use knet\ArcaModels\Agent;
use knet\Helpers\AgentFltUtils;
use knet\Helpers\PdfReport;
use knet\Helpers\RedisUser;
use knet\Mail\SendPortfolioListDoc;
use knet\Mail\SendReport;

class MakeStatScadenzeToSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $thisYear;
    private $prevYear;
    private $dStartMonth;
    private $dEndMonth;
    private $cumulativo;
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
        $this->cumulativo = $cumulativo;
        Log::info('MakeStatScadenzeToSend Job Created');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('MakeStatScadenzeToSend Job Started');

        Auth::loginUsingId($this->user->id);
        RedisUser::store();
        if (Auth::check() && RedisUser::exist()) {
            $lang = RedisUser::get('lang') !== null && RedisUser::get('lang') != '' ? RedisUser::get('lang') : App::getLocale(); // $locationLang;
        }
        App::setLocale($lang);

        // $attachReport = $this->createReport();
    

        try {
            Log::info('Invio Report StatScadenze AgCustomer - ' . $this->user->name);
            // TODO
            // $isInvio = ((!$user->auto_email && $client->fat_email) || ($user->auto_email && $user->auto_email));
            $fileToAttach = $this->createReport();
            $mail = (new SendReport($this->user, $fileToAttach, 'Situazione Scadenze'))->onQueue('emails');
            if (App::environment(['local', 'staging'])) {
                $toEmail = 'luca.ciotti@gmail.com';
                Mail::to($toEmail)->queue($mail);
                // Mail::to($toEmail)->cc(['emanuela.prioli@k-group.com'])->queue($mail);
                Log::info('Invio Report StatScadenze AgCustomer:' . $this->user->name . 'MailedJob to ' . $toEmail);
                } else {
                $toEmail = $this->user->email;
                // $toEmail = 'luca.ciotti@gmail.com';
                Mail::to($toEmail)->cc(['emanuela.prioli@k-group.com'])->bcc(['luca.ciotti@gmail.com'])->queue($mail);
                Log::info('Invio Report StatScadenze AgCustomer:' . $this->user->name . 'MailedJob to ' . $toEmail);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        Log::info('SendStatScadenzeListByEmail Job Ended');
    }
    protected function createReport()
    {
        $thisYear = Carbon::now()->year;

        $agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', Carbon::now())->orderBy('codice')->get();
        $fltAgents = $agents->pluck('codice')->toArray(); //$agents->pluck('codice')->toArray();
        $fltAgents = AgentFltUtils::checkSpecialRules($fltAgents);

        $startDate = Carbon::createFromDate($thisYear, 1, 1);
        $endDate = Carbon::now();

        $scads_TY = ScadCli::select(
            'id',
            'id_doc',
            'numfatt',
            'datafatt',
            'datascad',
            'codcf',
            'tipomod',
            'tipo',
            'insoluto',
            'u_insoluto',
            'pagato',
            'impeffval',
            'importopag',
            'idragg',
            'tipoacc',
            'impprovlit',
            'impprovliq',
            'liquidate',
            DB::raw('MONTH(datascad) as Mese')
        )
            ->whereBetween('datascad', array($startDate, $endDate));
        if ($fltAgents) {
            $scads_TY->whereIn('codag', $fltAgents);
        }
        $scads_TY = $scads_TY->whereIn('tipoacc', ['F', ''])
            ->whereRaw("`pagato` = 0")
            ->with(array('client' => function ($query) {
                $query->select('codice', 'descrizion')
                    ->withoutGlobalScope('agent')
                    ->withoutGlobalScope('superAgent')
                    ->withoutGlobalScope('client');
            }, 'agent', 'storia'))
            ->orderBy('datascad', 'asc')->orderBy('id', 'desc')
            ->get();
        $scads_TY = $scads_TY->groupBy('Mese');
        // dd($provv_TY);

        //ANNO PRECEDENTE
        $startDate = Carbon::createFromDate($thisYear - 1, 1, 1);
        $endDate = Carbon::createFromDate($thisYear - 1, 12, 31);

        $scads_PY = ScadCli::select(
            'id',
            'id_doc',
            'numfatt',
            'datafatt',
            'datascad',
            'codcf',
            'tipomod',
            'tipo',
            'insoluto',
            'u_insoluto',
            'pagato',
            'impeffval',
            'importopag',
            'idragg',
            'tipoacc',
            'impprovlit',
            'impprovliq',
            'liquidate',
            DB::raw('MONTH(datascad) as Mese')
        )
            ->whereBetween('datascad', array($startDate, $endDate));
        if ($fltAgents) {
            $scads_PY->whereIn('codag', $fltAgents);
        }
        $scads_PY = $scads_PY->whereIn('tipoacc', ['F', ''])
            ->whereRaw("`pagato` = 0")
            ->with(array('client' => function ($query) {
                $query->select('codice', 'descrizion')
                    ->withoutGlobalScope('agent')
                    ->withoutGlobalScope('superAgent')
                    ->withoutGlobalScope('client');
            }, 'agent', 'storia'))
            ->orderBy('datascad', 'asc')->orderBy('id', 'desc')
            ->get();
        $scads_PY = $scads_PY->groupBy('Mese');

        $title = "Scheda Scadenze";
        $subTitle = $this->user->name;
        $view = '_exports.pdf.schedaScadPdf';
        $filename = "Report_" . $title . "_" . $subTitle;
        $data = [
            'descrAg' => $subTitle,
            'thisYear' => $thisYear,
            'scads_TY' => $scads_TY,
            'scads_PY' => $scads_PY
        ];
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);
        if (Storage::exists('ReportPDFToSend/' . $filename . '.pdf')) {
            Storage::delete('ReportPDFToSend/' . $filename . '.pdf');
        }
        $pdf->save(storage_path('app') . '/' . 'ReportPDFToSend/' . $filename . '.pdf');

        return storage_path('app') . '/' . 'ReportPDFToSend/' . $filename . '.pdf';
    }
}
