<?php

namespace knet\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use knet\Jobs\emails\MakePortfolioAgCustomerToSend;
use knet\Jobs\emails\MakePortfolioListDocToSend;
use knet\Jobs\emails\MakeStatFatByCustomerToSend;
use knet\ReportsList;
use knet\UserAutoReports;

class FetchReportToSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $period;
    /**
     * Create a new job instance.
     *  $period can be (weekly, monthly, quarterly)
     * @return void
     */
    public function __construct($period)
    {
        $this->period = $period;
        // Log::info('FetchReportToSend Job Created - '. $period);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('FetchReportToSend Job Started - ' . $this->period);
        $listOfreports = ReportsList::where('active', 1)->where('period', $this->period)->get();
        foreach ($listOfreports as $report) {
            $listOfUsers = UserAutoReports::where('report_id', $report->id)->where('active', 1)->get();
            foreach ($listOfUsers as $userReport) {
                $method = 'do_'.$report->name;
                $this->$method($userReport->user);
            }
        }
    }
    
    public function do_portfolioListDoc($user){
        Log::info('Creo MakePortfolioListDocToSend per - ' . $user->name);
        MakePortfolioListDocToSend::dispatch($user)->onQueue('jobs');
    }
    
    public function do_portfolioAgByCustomerMonth($user){
        Log::info('Creo Portfolio AgByCustomer Mese per - ' . $user->name);
        MakePortfolioAgCustomerToSend::dispatch($user)->onQueue('jobs');
    }
    
    public function do_portfolioAgByCustomerYear($user){
        Log::info('Creo Portfolio AgByCustomer Anno per - ' . $user->name);
        MakePortfolioAgCustomerToSend::dispatch($user, true)->onQueue('jobs');
    }
    
    public function do_statFatByCustomer($user){
        Log::info('Creo Stat Fatturato ByCustomer per - ' . $user->name);
        MakeStatFatByCustomerToSend::dispatch($user)->onQueue('jobs');
    }
}
