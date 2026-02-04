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
use knet\ArcaModels\DocRow;
use knet\Helpers\AgentFltUtils;
use knet\Helpers\PdfReport;
use knet\Helpers\RedisUser;
use knet\Helpers\Utils;
use knet\Mail\SendPortfolioListDoc;
use knet\Mail\SendReport;

class MakePortfolioAgCustomerToSend implements ShouldQueue
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
        Log::info('MakePortfolioAgCustomerToSend Job Created');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('MakePortfolioAgCustomerToSend Job Started');

        Auth::loginUsingId($this->user->id);
        RedisUser::store();

        // $attachReport = $this->createReport();
    

        try {
            Log::info('Invio Report Portfolio AgCustomer - ' . $this->user->name);
            // TODO
            // $isInvio = ((!$user->auto_email && $client->fat_email) || ($user->auto_email && $user->auto_email));
            $fileToAttach = $this->createReport();
            $mail = (new SendReport($this->user, $fileToAttach, 'Portofolio Clienti'))->onQueue('emails');
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

        $agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', Carbon::now())->orderBy('codice')->get();
        $fltAgents = $agents->pluck('codice')->toArray(); //$agents->pluck('codice')->toArray();
        $fltAgents = AgentFltUtils::checkSpecialRules($fltAgents);

        $ord = $this->getOrderToShip(['A', 'B', 'D0'], $fltAgents, ['Z'])->sortBy('doccli.codicecf')->groupBy('doccli.codicecf')->mapWithKeys(function ($group, $key) {
            return collect([
                $key =>
                collect([
                    'codicecf' => $key,
                    'client' => $group->first()->doccli->client,
                    'totOrd' => $group->sum('totRowPrice'),
                    'n_docOrd' => $group->groupBy('doccli.id')->count()
                ])
            ]);
        });

        $ddt = $this->getDdtNotInvoiced(['A',
            'B',
            'D0'
        ], $fltAgents, ['Z'])->sortBy('doccli.codicecf')->groupBy('doccli.codicecf')->mapWithKeys(function ($group, $key) {
            return collect([
                $key =>
                collect([
                    'codicecf' => $key,
                    'client' => $group->first()->doccli->client,
                    'totDdt' => $group->sum('totRowPrice'),
                    'n_docDdt' => $group->groupBy('doccli.id')->count()
                ])
            ]);
        });

        $fatt = $this->getInvoice(['A', 'B',
            'D0'
        ], $fltAgents, ['Z'])->sortBy('doccli.codicecf')->groupBy('doccli.codicecf')->mapWithKeys(function ($group, $key) {
            return collect([
                $key =>
                collect([
                    'codicecf' => $key,
                    'client' => $group->first()->doccli->client,
                    'totFat' => $group->sum('totRowPrice'),
                    'n_docFat' => $group->groupBy('doccli.id')->count()
                ])
            ]);
        });

        $portfolio = $ord->union($fatt)->union($ddt)->map(function ($c, $key) use ($fatt, $ddt) {
            if ($fatt->has($key)) {
                return $c->union($fatt[$key]);
            } else {
                return $c->put('totFat', 0);
            }
            if ($ddt->has($key)) {
                return $c->union($ddt[$key]);
            }
            return $c;
        })->sortByDesc('totFat');

        $title = "Portafoglio Clienti";
        $subTitle = "";
        $view = '_exports.pdf.portfolioCliPdf';
        $filename = "Report_" . $title . "_" . $subTitle;
        $data = [
            'agents' => $agents,
            'mese' => $mese,
            'cumulativo' => $this->cumulativo,
            'thisYear' => $this->thisYear,
            'prevYear' => $this->prevYear,
            'fltAgents' => $fltAgents,
            'portfolio' => $portfolio
        ];
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);
        if (Storage::exists('ReportPDFToSend/' . $filename . '.pdf')) {
            Storage::delete('ReportPDFToSend/' . $filename . '.pdf');
        }
        $pdf->save(storage_path('app') . '/' . 'ReportPDFToSend/' . $filename . '.pdf');
        return storage_path('app') . '/' . 'ReportPDFToSend/' . $filename . '.pdf';
    }

    /* Restituisce tutti gli ordini che devono essere evasi in funzione di alcune condizioni
		1. $agents -> Array di Agenti [Optional]
		2. $filiali -> Boolean [Default -> false]
		3. $grupIn -> Array con iniziale del prodotti es. ['A', 'B', 'B06'] 
		4. $grupNotIn -> Array con iniziali dei gruppi prodotto da escludere [Optional]
	 */
    public function getOrderToShip($grupIn, $agents = [], $grupNotIn = [], $filiali = false)
    {

        // Mi costruisco l'array delle teste dei documenti da cercare se Non esiste
        if (empty($this->arrayIDOC)) {
            $docTes = DocCli::select('id')
                ->whereIn('esercizio', [(string)$this->thisYear, (string)$this->prevYear])
                ->where('tipodoc', 'OC');
            if (!$filiali && RedisUser::get('ditta_DB') == 'knet_it') {
                $docTes->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
            }
            if (!empty($agents)) {
                $docTes->whereIn('agente', $agents);
            }
            $docTes = $docTes->get();
            $this->arrayIDOC = $docTes->toArray();
        }

        //Costruisco infine le righe con i dati che mi servono
        $docRow = DocRow::select('id_testa', 'codicearti', 'descrizion', 'gruppo', 'prezzoun', 'prezzotot', 'sconti', 'quantitare', 'unmisura', 'quantita', 'u_dtpronto', 'dataconseg')
        ->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
        ->with(['doccli' => function ($q) {
            $q->select('id', 'tipomodulo', 'codicecf', 'sconti', 'scontocass', 'numerodoc')->with('client');
        }])
            ->whereHas('product', function ($q) {
                $q->orWhere('u_artlis', 1)->orWhere('u_perscli', 1);
            })
            ->where('quantitare', '>', 0)
            ->where('ommerce', 0)
            ->where('codicearti', '!=', '');
        if (!empty($grupIn)) {
            $docRow->where(function ($q) use ($grupIn) {
                $q->where('gruppo', 'like', $grupIn[0] . '%');
                if (count($grupIn) > 1) {
                    for ($i = 1; $i < count($grupIn); $i++) {
                        $q->orWhere('gruppo', 'like', $grupIn[$i] . '%');
                    }
                }
            });
        }
        if (!empty($grupNotIn)) {
            $docRow->where(function ($q) use ($grupNotIn) {
                $q->where('gruppo', 'NOT LIKE', $grupNotIn[0] . '%');
                if (count($grupNotIn) > 1) {
                    for ($i = 1; $i < count($grupNotIn); $i++) {
                        $q->where('gruppo', 'NOT LIKE', $grupNotIn[$i] . '%');
                    }
                }
            });
        }
        // 26/11 Richiesta di Mauro per fare Ordini solo del mese!
        $docRow->where('dataconseg', '<=', $this->dEndMonth);
        // $docRow->whereBetween('dataconseg', [$this->dStartMonth, $this->dEndMonth]);
        $docRow = $docRow->whereIn('id_testa', $this->arrayIDOC)->get();

        $docRow = $this->calcTotRowPrice($docRow, true);
        return $docRow;
    }

    /* Restituisce tutte le bolle che devono essere fatturate in funzione di alcune condizioni
		1. $agents -> Array di Agenti [Optional]
		2. $filiali -> Boolean [Default -> false]
		3. $grupIn -> Array con iniziale del prodotti es. ['A', 'B', 'B06'] 
	 */
    public function getDdtNotInvoiced($grupIn, $agents = [], $grupNotIn = [], $filiali = false)
    {
        // Mi costruisco l'array delle teste dei documenti da cercare
        if (empty($this->arrayIDBO)) {
            $docTes = DocCli::select('id')
                ->whereIn('esercizio', [(string)$this->thisYear])
                ->whereIn('tipodoc', ['BO', 'BR']);
            if (!$filiali && RedisUser::get('ditta_DB') == 'knet_it') {
                $docTes->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
            }
            if (!empty($agents)) {
                $docTes->whereIn('agente', $agents);
            }
            $docTes->whereBetween('datadoc', [$this->dStartMonth, $this->dEndMonth]);
            $docTes = $docTes->get();
            $this->arrayIDBO = $docTes->toArray();
        }

        //Costruisco infine le righe con i dati che mi servono
        $docRow = DocRow::select('id_testa', 'codicearti', 'prezzoun', 'prezzotot', 'sconti', 'quantitare')
        ->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
        ->with(['doccli' => function ($q) {
            $q->select('id', 'tipomodulo', 'codicecf', 'sconti', 'scontocass', 'numerodoc')->with('client');
        }])
            ->whereHas('product', function ($q) {
                $q->orWhere('u_artlis', 1)->orWhere('u_perscli', 1);
            })
            ->where('quantitare', '>', 0)
            ->where('ommerce', 0)
            ->where('codicearti', '!=', '');
        if (!empty($grupIn)) {
            $docRow->where(function ($q) use ($grupIn) {
                $q->where('gruppo', 'like', $grupIn[0] . '%');
                if (count($grupIn) > 1) {
                    for ($i = 1; $i < count($grupIn); $i++) {
                        $q->orWhere('gruppo', 'like', $grupIn[$i] . '%');
                    }
                }
            });
        }
        if (!empty($grupNotIn)) {
            $docRow->where(function ($q) use ($grupNotIn) {
                $q->where('gruppo', 'NOT LIKE', $grupNotIn[0] . '%');
                if (count($grupNotIn) > 1) {
                    for ($i = 1; $i < count($grupNotIn); $i++) {
                        $q->where('gruppo', 'NOT LIKE', $grupNotIn[$i] . '%');
                    }
                }
            });
        }
        $docRow = $docRow->whereIn('id_testa', $this->arrayIDBO)->get();

        $docRow = $this->calcTotRowPrice($docRow);
        return $docRow;
    }

    /* Restituisce tutte le fatture in funzione di alcune condizioni
		1. $agents -> Array di Agenti [Optional]
		2. $filiali -> Boolean [Default -> false]
		3. $grupIn -> Array con iniziale del prodotti es. ['A', 'B', 'B06'] 
	 */
    public function getInvoice($grupIn, $agents = [], $grupNotIn = [], $filiali = false)
    {
        // Mi costruisco l'array delle teste dei documenti da cercare
        if (empty($this->arrayIDFT)) {
            $docTes = DocCli::select('id')
                ->whereIn('esercizio', [(string)$this->thisYear])
                ->whereIn('tipodoc', ['FT', 'FE', 'NC', 'NE', 'EQ', 'EF', 'NB', 'NX']);
            if (!$filiali && RedisUser::get('ditta_DB') == 'knet_it') {
                $docTes->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
            }
            if (!empty($agents)) {
                $docTes->whereIn('agente', $agents);
            }
            $docTes->whereBetween('datadoc', [$this->dStartMonth, $this->dEndMonth]);
            $docTes = $docTes->get();
            $this->arrayIDFT = $docTes->toArray();
        }

        //Costruisco infine le righe con i dati che mi servono
        $docRow = DocRow::select('id_testa', 'codicearti', 'prezzoun', 'prezzotot', 'sconti', 'quantitare', 'quantita')
        ->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
        ->with(['doccli' => function ($q) {
            $q->select('id', 'tipomodulo', 'codicecf', 'sconti', 'scontocass', 'numerodoc')->with('client');
        }])
            ->whereHas('product', function ($q) {
                $q->orWhere('u_artlis', 1)->orWhere('u_perscli', 1);
            })
            ->whereRaw('(LEFT(codicearti,4) != ? AND LEFT(codicearti,4) != ?)', ['CAMP', 'NOTA'])
            ->where('quantitare', '>', 0)
            ->where('ommerce', 0)
            ->where('codicearti', '!=', '');
        if (!empty($grupIn)) {
            $docRow->where(function ($q) use ($grupIn) {
                $q->where('gruppo', 'like', $grupIn[0] . '%');
                if (count($grupIn) > 1) {
                    for ($i = 1; $i < count($grupIn); $i++) {
                        $q->orWhere('gruppo', 'like', $grupIn[$i] . '%');
                    }
                }
            });
        }
        if (!empty($grupNotIn)) {
            $docRow->where(function ($q) use ($grupNotIn) {
                $q->where('gruppo', 'NOT LIKE', $grupNotIn[0] . '%');
                if (count($grupNotIn) > 1) {
                    for ($i = 1; $i < count($grupNotIn); $i++) {
                        $q->where('gruppo', 'NOT LIKE', $grupNotIn[$i] . '%');
                    }
                }
            });
        }
        $docRow = $docRow->whereIn('id_testa', $this->arrayIDFT)->get();

        $docRow = $this->calcTotRowPrice($docRow);
        return $docRow;
    }

    /* Fatture dell'anno precedente */
    public function getPrevInvoice($grupIn, $agents = [], $grupNotIn = [], $filiali = false)
    {
        // Mi costruisco l'array delle teste dei documenti da cercare
        $dStartDate = $this->dStartMonth;
        $dEndDate = $this->dEndMonth;
        $dStartDate = $dStartDate->subYear();
        $dEndDate = new Carbon('last day of ' . $dEndDate->format('F') . ' ' . ((string)$this->prevYear));
        if (empty($this->arrayIDprevFT)) {
            $docTes = DocCli::select('id')
                ->whereIn('esercizio', [(string)$this->prevYear])
                ->whereIn('tipodoc', ['FT', 'FE', 'NC', 'NE', 'EQ', 'EF', 'NB', 'NX']);
            if (!$filiali && RedisUser::get('ditta_DB') == 'knet_it') {
                $docTes->whereNotIn('codicecf', ['C00973', 'C03000', 'C07000', 'C06000', 'C01253']);
            }
            if (!empty($agents)) {
                $docTes->whereIn('agente', $agents);
            }
            $docTes->whereBetween('datadoc', [$dStartDate, $dEndDate]);
            $docTes = $docTes->get();
            $this->arrayIDprevFT = $docTes->toArray();
        }
        //Costruisco infine le righe con i dati che mi servono
        $docRow = DocRow::select('id_testa', 'codicearti', 'prezzoun', 'prezzotot',  'sconti', 'quantitare')
        ->addSelect(DB::raw('prezzoun*0 as totRowPrice'))
        ->with(['doccli' => function ($q) {
            $q->select('id', 'tipomodulo', 'sconti', 'scontocass', 'numerodoc');
        }])
            ->whereHas('product', function ($q) {
                $q->orWhere('u_artlis', 1)->orWhere('u_perscli', 1);
            })
            ->where('quantitare', '>', 0)
            ->where('ommerce', 0)
            ->where('codicearti', '!=', '');
        if (!empty($grupIn)) {
            $docRow->where(function ($q) use ($grupIn) {
                $q->where('gruppo', 'like', $grupIn[0] . '%');
                if (count($grupIn) > 1) {
                    for ($i = 1; $i < count($grupIn); $i++) {
                        $q->orWhere('gruppo', 'like', $grupIn[$i] . '%');
                    }
                }
            });
        }
        if (!empty($grupNotIn)) {
            $docRow->where(function ($q) use ($grupNotIn) {
                $q->where('gruppo', 'NOT LIKE', $grupNotIn[0] . '%');
                if (count($grupNotIn) > 1) {
                    for ($i = 1; $i < count($grupNotIn); $i++) {
                        $q->where('gruppo', 'NOT LIKE', $grupNotIn[$i] . '%');
                    }
                }
            });
        }
        $docRow = $docRow->whereIn('id_testa', $this->arrayIDprevFT)->get();

        $docRow = $this->calcTotRowPrice($docRow);
        // dd($docRow);
        return $docRow;
    }

    /* 
		Calcola il Prezzo totale di ogni riga applicando tutti gli sconti del documento
		inclusi Extra Sconti Cassa o Merce
	 */
    public function calcTotRowPrice($collect, $usePrezzoUn = false)
    {
        foreach ($collect as $row) {
            $fattoreMolt = ($row->doccli->tipomodulo == 'N' ? -1 : 1);
            if ($usePrezzoUn) {
                $unitRowPrice = Utils::scontaDel(Utils::scontaDel(Utils::scontaDel($row->prezzoun, $row->sconti, 4), $row->doccli->sconti, 3), $row->doccli->scontocass, 3);
                $row->totRowPrice = (float)round(($unitRowPrice * $row->quantitare * $fattoreMolt), 2);
            } else {
                $totRowPrice = Utils::scontaDel(Utils::scontaDel($row->prezzotot, $row->doccli->sconti, 3), $row->doccli->scontocass, 3);
                $row->totRowPrice = (float)round($totRowPrice * $fattoreMolt, 2);
            }
        }
        return $collect;
    }
}
