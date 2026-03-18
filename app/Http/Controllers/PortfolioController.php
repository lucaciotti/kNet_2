<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use knet\ArcaModels\Agent;
use knet\ArcaModels\DocCli;
use knet\ArcaModels\DocRow;
use knet\Helpers\AgentFltUtils;
use knet\Helpers\PdfReport;
use knet\Helpers\Utils;
use knet\Helpers\RedisUser;
use knet\Helpers\DocFilters;
use knet\Helpers\DocRowUtils;
use knet\Helpers\DocTesUtils as DocUtils;

class PortfolioController extends Controller
{
	protected $thisYear;
	protected $prevYear;
	protected $dStartMonth;
	protected $dEndMonth;
	protected $arrayIDOC;
	protected $arrayIDBO;
	protected $arrayIDFT;
	protected $arrayIDprevFT;

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function idxAg(Request $req, $codAg = null)
	{
		// Costruisco i filtri
		$this->thisYear = (!$req->input('year') ? Carbon::now()->year : $req->input('year'));
		$this->prevYear = $this->thisYear - 1;
		$this->dStartMonth = new Carbon('first day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
		$this->dEndMonth = new Carbon('last day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
		$mese = (!$req->input('mese') ? Carbon::now()->month : $req->input('mese'));
		if ($mese) {
			$this->dStartMonth = new Carbon('first day of ' . Carbon::createFromDate(null, $mese, null)->format('F') . ' ' . ((string)$this->thisYear));
			$this->dEndMonth = new Carbon('last day of ' . Carbon::createFromDate(null, $mese, null)->format('F') . ' ' . ((string)$this->thisYear));
		}
		if ($req->input('cumulativo')) {
			$this->dStartMonth = new Carbon('first day of january ' . ((string)$this->thisYear));
		}

		$dataFineAgente = Carbon::createFromDate($this->prevYear, 1, 1);
		$agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', $dataFineAgente)->orderBy('codice')->get();
		$codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
		$fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agents->first()->codice)); //$agents->pluck('codice')->toArray();
		$fltAgents = AgentFltUtils::checkSpecialRules($fltAgents);


		$defaultDocFilter = new DocFilters();
		$defaultDocFilter->addArrayFilter('agente', $fltAgents);
		$defaultDocFilter->addBoolFilter('u_artlis&u_perslis', 1);
		$defaultDocFilter->addBoolFilter('ommerce', 0);
		$defaultDocFilter->addBoolFilter('filiali', 0);
		$defaultDocFilter->addStringFilter('codicearti', 'notEql', '');
		
		$defaultDocFilterKrona = clone $defaultDocFilter;
		$defaultDocFilterKrona->addArrayFilter('prGroupIncl', ['A']);
		$defaultDocFilterKrona->addArrayFilter('prGroupExcl', ['A99', 'A14']);
		$defaultDocFilterKrona->addStringFilter('codicearti', 'notStw', 'CAMP');
		$defaultDocFilterSpinOff = clone $defaultDocFilter;
		$defaultDocFilterSpinOff->addArrayFilter('prGroupIncl', ['A14']);
		$defaultDocFilterSpinOff->addStringFilter('codicearti', 'notStw', 'CAMP');
		$defaultDocFilterKoblenz = clone $defaultDocFilter;
		$defaultDocFilterKoblenz->addArrayFilter('prGroupIncl', ['B']);
		$defaultDocFilterKoblenz->addArrayFilter('prGroupExcl', ['B99', 'B06']);
		$defaultDocFilterKoblenz->addStringFilter('codicearti', 'notStw', 'CAMP');
		$defaultDocFilterBonusKrona = clone $defaultDocFilter;
		$defaultDocFilterBonusKrona->addArrayFilter('prGroupIncl', ['A99']);
		$defaultDocFilterBonusKoblenz = clone $defaultDocFilter;
		$defaultDocFilterBonusKoblenz->addArrayFilter('prGroupIncl', ['B99']);;
		$defaultDocFilterKubica = clone $defaultDocFilter;
		$defaultDocFilterKubica->addArrayFilter('prGroupIncl', ['B06']);
		$defaultDocFilterKubica->addArrayFilter('prGroupExcl', ['B0630']);
		$defaultDocFilterKubica->addStringFilter('codicearti', 'notStw', 'CAMP');
		$defaultDocFilterAtomica = clone $defaultDocFilter;
		$defaultDocFilterAtomica->addArrayFilter('prGroupIncl', ['B0630']);
		$defaultDocFilterAtomica->addStringFilter('codicearti', 'notStw', 'CAMP');
		$defaultDocFilterPlanet = clone $defaultDocFilter;
		$defaultDocFilterPlanet->addArrayFilter('prGroupIncl', ['D0']);
		$defaultDocFilterPlanet->addStringFilter('codicearti', 'notStw', 'CAMP');
		$defaultDocFilterDIC = clone $defaultDocFilter;
		$defaultDocFilterDIC->addArrayFilter('prGroupIncl', ['Z']);
		$defaultDocFilterCAMP = clone $defaultDocFilter;
		$defaultDocFilterCAMP->addStringFilter('codicearti', 'stw', 'CAMP');

		// $OCKronaCls = (new DocUtils($defaultDocFilterKrona));
		// $OCKrona = (new DocUtils($defaultDocFilterKrona))->getOrderToShip($this->dEndMonth)->sum('totValO');
		// dd($defaultDocFilterKrona->toString());
		$OCKrona = (new DocRowUtils($defaultDocFilterKrona))->getOrderToShip($this->dEndMonth)->sum('totNetVal.O');
		// dd($OCKrona);
		$OCSpinOff = (new DocRowUtils($defaultDocFilterSpinOff))->getOrderToShip($this->dEndMonth)->sum('totNetVal.O');
		$OCKoblenz = (new DocRowUtils($defaultDocFilterKoblenz))->getOrderToShip($this->dEndMonth)->sum('totNetVal.O');
		$OCBonusKrona = (new DocRowUtils($defaultDocFilterBonusKrona))->getOrderToShip($this->dEndMonth)->sum('totNetVal.O');
		$OCBonusKoblenz = (new DocRowUtils($defaultDocFilterBonusKoblenz))->getOrderToShip($this->dEndMonth)->sum('totNetVal.O');
		$OCKubica = (new DocRowUtils($defaultDocFilterKubica))->getOrderToShip($this->dEndMonth)->sum('totNetVal.O');
		$OCAtomika = (new DocRowUtils($defaultDocFilterAtomica))->getOrderToShip($this->dEndMonth)->sum('totNetVal.O');
		$OCPlanet = (RedisUser::get('ditta_DB') == 'kNet_es') ? (new DocRowUtils($defaultDocFilterPlanet))->getOrderToShip($this->dEndMonth)->sum('totNetVal.O') : 0;
		$OCDIC = (new DocRowUtils($defaultDocFilterDIC))->getOrderToShip($this->dEndMonth)->sum('totNetVal.O');
		$OCCAMP = (new DocRowUtils($defaultDocFilterCAMP))->getOrderToShip($this->dEndMonth)->sum('totNetVal.O');

		$BOKrona = (new DocRowUtils($defaultDocFilterKrona))->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth)->sum('totNetVal.B');
		$BOSpinOff = (new DocRowUtils($defaultDocFilterSpinOff))->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth)->sum('totNetVal.B');
		$BOKoblenz = (new DocRowUtils($defaultDocFilterKoblenz))->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth)->sum('totNetVal.B');
		$BOBonusKrona = (new DocRowUtils($defaultDocFilterBonusKrona))->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth)->sum('totNetVal.B');
		$BOBonusKoblenz = (new DocRowUtils($defaultDocFilterBonusKoblenz))->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth)->sum('totNetVal.B');
		$BOKubica = (new DocRowUtils($defaultDocFilterKubica))->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth)->sum('totNetVal.B');
		$BOAtomika = (new DocRowUtils($defaultDocFilterAtomica))->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth)->sum('totNetVal.B');
		$BOPlanet = (RedisUser::get('ditta_DB') == 'kNet_es') ? (new DocRowUtils($defaultDocFilterPlanet))->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth)->sum('totNetVal.B') : 0;
		$BODIC = (new DocRowUtils($defaultDocFilterDIC))->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth)->sum('totNetVal.B');
		$BOCAMP = (new DocRowUtils($defaultDocFilterCAMP))->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth)->sum('totNetVal.B');

		$FTKronaObj = (new DocRowUtils($defaultDocFilterKrona))->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$FTSpinOffObj = (new DocRowUtils($defaultDocFilterSpinOff))->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$FTKoblenzObj = (new DocRowUtils($defaultDocFilterKoblenz))->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$FTBonusKronaObj = (new DocRowUtils($defaultDocFilterBonusKrona))->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$FTBonusKoblenzObj = (new DocRowUtils($defaultDocFilterBonusKoblenz))->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$FTKubicaObj = (new DocRowUtils($defaultDocFilterKubica))->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$FTAtomikaObj = (new DocRowUtils($defaultDocFilterAtomica))->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$FTPlanetObj = (RedisUser::get('ditta_DB') == 'kNet_es') ? (new DocRowUtils($defaultDocFilterPlanet))->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth) : 0;
		$FTDICObj = (new DocRowUtils($defaultDocFilterDIC))->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$FTCAMPObj = (new DocRowUtils($defaultDocFilterCAMP))->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$FTKrona = $FTKronaObj->sum('totNetVal.F') + $FTKronaObj->sum('totNetVal.N');
		$FTSpinOff = $FTSpinOffObj->sum('totNetVal.F') + $FTSpinOffObj->sum('totNetVal.N');
		$FTKoblenz = $FTKoblenzObj->sum('totNetVal.F') + $FTKoblenzObj->sum('totNetVal.N');
		$FTBonusKrona = $FTBonusKronaObj->sum('totNetVal.F') + $FTBonusKronaObj->sum('totNetVal.N');
		$FTBonusKoblenz = $FTBonusKoblenzObj->sum('totNetVal.F') + $FTBonusKoblenzObj->sum('totNetVal.N');
		$FTKubica = $FTKubicaObj->sum('totNetVal.F') + $FTKubicaObj->sum('totNetVal.N');
		$FTAtomika = $FTAtomikaObj->sum('totNetVal.F') + $FTAtomikaObj->sum('totNetVal.N');
		$FTPlanet = (RedisUser::get('ditta_DB') == 'kNet_es') ? $FTPlanetObj->sum('totNetVal.F') + $FTPlanetObj->sum('totNetVal.N') : 0;
		$FTDIC = $FTDICObj->sum('totNetVal.F') + $FTDICObj->sum('totNetVal.N');
		$FTCAMP = $FTCAMPObj->sum('totNetVal.F') + $FTCAMPObj->sum('totNetVal.N');

		$dPrevStartMonth = (clone $this->dStartMonth)->subYear();
		$dPrevEndMonth = (clone $this->dEndMonth)->subYear();

		$FTPrevKronaObj = (new DocRowUtils($defaultDocFilterKrona))->getInvoice($this->prevYear, $dPrevStartMonth, $dPrevEndMonth);
		$FTPrevSpinOffObj = (new DocRowUtils($defaultDocFilterSpinOff))->getInvoice($this->prevYear, $dPrevStartMonth, $dPrevEndMonth);
		$FTPrevKoblenzObj = (new DocRowUtils($defaultDocFilterKoblenz))->getInvoice($this->prevYear, $dPrevStartMonth, $dPrevEndMonth);
		$FTPrevBonusKronaObj = (new DocRowUtils($defaultDocFilterBonusKrona))->getInvoice($this->prevYear, $dPrevStartMonth, $dPrevEndMonth);
		$FTPrevBonusKoblenzObj = (new DocRowUtils($defaultDocFilterBonusKoblenz))->getInvoice($this->prevYear, $dPrevStartMonth, $dPrevEndMonth);
		$FTPrevKubicaObj = (new DocRowUtils($defaultDocFilterKubica))->getInvoice($this->prevYear, $dPrevStartMonth, $dPrevEndMonth);
		$FTPrevAtomikaObj = (new DocRowUtils($defaultDocFilterAtomica))->getInvoice($this->prevYear, $dPrevStartMonth, $dPrevEndMonth);
		$FTPrevPlanetObj = (RedisUser::get('ditta_DB') == 'kNet_es') ? (new DocRowUtils($defaultDocFilterPlanet))->getInvoice($this->prevYear, $dPrevStartMonth, $dPrevEndMonth) : 0;
		$FTPrevDICObj = (new DocRowUtils($defaultDocFilterDIC))->getInvoice($this->prevYear, $dPrevStartMonth, $dPrevEndMonth);
		$FTPrevCAMPObj = (new DocRowUtils($defaultDocFilterCAMP))->getInvoice($this->prevYear, $dPrevStartMonth, $dPrevEndMonth);
		$FTPrevKrona = $FTPrevKronaObj->sum('totNetVal.F') + $FTPrevKronaObj->sum('totNetVal.N');
		$FTPrevSpinOff = $FTPrevSpinOffObj->sum('totNetVal.F') + $FTPrevSpinOffObj->sum('totNetVal.N');
		$FTPrevKoblenz = $FTPrevKoblenzObj->sum('totNetVal.F') + $FTPrevKoblenzObj->sum('totNetVal.N');
		$FTPrevBonusKrona = $FTPrevBonusKronaObj->sum('totNetVal.F') + $FTPrevBonusKronaObj->sum('totNetVal.N');
		$FTPrevBonusKoblenz = $FTPrevBonusKoblenzObj->sum('totNetVal.F') + $FTPrevBonusKoblenzObj->sum('totNetVal.N');
		$FTPrevKubica = $FTPrevKubicaObj->sum('totNetVal.F') + $FTPrevKubicaObj->sum('totNetVal.N');
		$FTPrevAtomika = $FTPrevAtomikaObj->sum('totNetVal.F') + $FTPrevAtomikaObj->sum('totNetVal.N');
		$FTPrevPlanet = (RedisUser::get('ditta_DB') == 'kNet_es') ? $FTPrevPlanetObj->sum('totNetVal.F') + $FTPrevPlanetObj->sum('totNetVal.N') : 0;
		$FTPrevDIC = $FTPrevDICObj->sum('totNetVal.F') + $FTPrevDICObj->sum('totNetVal.N');
		$FTPrevCAMP = $FTPrevCAMPObj->sum('totNetVal.F') + $FTPrevCAMPObj->sum('totNetVal.N');

		return view('portfolio.idxAg', [
			'agents' => $agents,
			'mese' => $mese,
			'cumulativo' => $req->input('cumulativo'),
			'thisYear' => $this->thisYear,
			'prevYear' => $this->prevYear,
			'fltAgents' => $fltAgents,
			'OCKrona' => $OCKrona,
			'OCSpinOff' => $OCSpinOff,
			'OCKoblenz' => $OCKoblenz,
			'OCBonusKrona' => $OCBonusKrona,
			'OCBonusKoblenz' => $OCBonusKoblenz,
			'OCKubica' => $OCKubica,
			'OCAtomika' => $OCAtomika,
			'OCPlanet' => $OCPlanet,
			'OCDIC' => $OCDIC,
			'OCCAMP' => $OCCAMP,
			'BOKrona' => $BOKrona,
			'BOSpinOff' => $BOSpinOff,
			'BOKoblenz' => $BOKoblenz,
			'BOBonusKrona' => $BOBonusKrona,
			'BOBonusKoblenz' => $BOBonusKoblenz,
			'BOKubica' => $BOKubica,
			'BOAtomika' => $BOAtomika,
			'BOPlanet' => $BOPlanet,
			'BODIC' => $BODIC,
			'BOCAMP' => $BOCAMP,
			'FTKrona' => $FTKrona,
			'FTSpinOff' => $FTSpinOff,
			'FTKoblenz' => $FTKoblenz,
			'FTBonusKrona' => $FTBonusKrona,
			'FTBonusKoblenz' => $FTBonusKoblenz,
			'FTKubica' => $FTKubica,
			'FTAtomika' => $FTAtomika,
			'FTPlanet' => $FTPlanet,
			'FTDIC' => $FTDIC,
			'FTCAMP' => $FTCAMP,
			'FTPrevKrona' => $FTPrevKrona,
			'FTPrevSpinOff' => $FTPrevSpinOff,
			'FTPrevKoblenz' => $FTPrevKoblenz,
			'FTPrevBonusKrona' => $FTPrevBonusKrona,
			'FTPrevBonusKoblenz' => $FTPrevBonusKoblenz,
			'FTPrevKubica' => $FTPrevKubica,
			'FTPrevAtomika' => $FTPrevAtomika,
			'FTPrevPlanet' => $FTPrevPlanet,
			'FTPrevDIC' => $FTPrevDIC,
			'FTPrevCAMP' => $FTPrevCAMP,
			'urlOrders' => action('DocCliController@showOrderDispachMonth', ['fltAgents' => $fltAgents, 'mese' => $mese, 'year' => $this->thisYear]),
			'urlDdts' => action('DocCliController@showDdtToInvoice', ['fltAgents' => $fltAgents]),
			'urlInvoices' => action('DocCliController@showInvoiceMonth', ['fltAgents' => $fltAgents, 'mese' => $mese, 'year' => $this->thisYear]),
			'urlInvoicesPrec' => action('DocCliController@showInvoiceMonth', ['fltAgents' => $fltAgents, 'mese' => $mese, 'year' => $this->prevYear]),
		]);
	}

	public function portfolioAgByCustomer(Request $req, $codAg = null)
	{
		// Costruisco i filtri
		$this->thisYear = (!$req->input('year') ? Carbon::now()->year : $req->input('year'));
		$this->prevYear = $this->thisYear - 1;
		$this->dStartMonth = new Carbon('first day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
		$this->dEndMonth = new Carbon('last day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
		$mese = (!$req->input('mese') ? Carbon::now()->month : $req->input('mese'));
		if ($mese) {
			$this->dStartMonth = new Carbon('first day of ' . Carbon::createFromDate(null, $mese, null)->format('F') . ' ' . ((string)$this->thisYear));
			$this->dEndMonth = new Carbon('last day of ' . Carbon::createFromDate(null, $mese, null)->format('F') . ' ' . ((string)$this->thisYear));
		}
		if ($req->input('cumulativo')) {
			$this->dStartMonth = new Carbon('first day of january ' . ((string)$this->thisYear));
		}

		$dataFineAgente = Carbon::createFromDate($this->prevYear, 1, 1);
		$agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', $dataFineAgente)->orderBy('codice')->get();
		$codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
		$fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agents->first()->codice)); //$agents->pluck('codice')->toArray();
		$fltAgents = AgentFltUtils::checkSpecialRules($fltAgents);

		$defaultDocFilter = new DocFilters();
		$defaultDocFilter->addArrayFilter('prGroupIncl', ['A', 'B', 'D0']);
		$defaultDocFilter->addArrayFilter('prGroupExcl', ['Z']);
		$defaultDocFilter->addArrayFilter('agente', $fltAgents);
		$defaultDocFilter->addBoolFilter('u_artlis&u_perslis', 1);
		$defaultDocFilter->addBoolFilter('ommerce', 0);
		$defaultDocFilter->addBoolFilter('filiali', 0);
		$defaultDocFilter->addStringFilter('codicearti', 'notEql', '');

		$ordUtils = (new DocRowUtils($defaultDocFilter));
		$ord = $ordUtils->getOrderToShip($this->dEndMonth);
		$ddtUtils = (new DocRowUtils($defaultDocFilter));
		$ddt = $ddtUtils->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$fattUtils = (new DocRowUtils($defaultDocFilter));
		$fatt = $fattUtils->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);

		$portfolio = DocRowUtils::buildDocsPortfolio($ord, $ddt, $fatt);
		// dd($portfolio);
		return view('portfolio.portfolioAgByCustomer', [
			'agents' => $agents,
			'mese' => $mese,
			'cumulativo' => $req->input('cumulativo'),
			'thisYear' => $this->thisYear,
			'prevYear' => $this->prevYear,
			'fltAgents' => $fltAgents,
			'portfolio' => $portfolio
		]);
	}

	public function portfolioAgByCustomerPDF(Request $req, $codAg = null)
	{
		// Costruisco i filtri
		$this->thisYear = (!$req->input('year') ? Carbon::now()->year : $req->input('year'));
		$this->prevYear = $this->thisYear - 1;
		$this->dStartMonth = new Carbon('first day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
		$this->dEndMonth = new Carbon('last day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
		$mese = (!$req->input('mese') ? Carbon::now()->month : $req->input('mese'));
		if ($mese) {
			$this->dStartMonth = new Carbon('first day of ' . Carbon::createFromDate(null, $mese, null)->format('F') . ' ' . ((string)$this->thisYear));
			$this->dEndMonth = new Carbon('last day of ' . Carbon::createFromDate(null, $mese, null)->format('F') . ' ' . ((string)$this->thisYear));
		}
		if ($req->input('cumulativo')) {
			$this->dStartMonth = new Carbon('first day of january ' . ((string)$this->thisYear));
		}

		$dataFineAgente = Carbon::createFromDate($this->prevYear, 1, 1);
		$agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', $dataFineAgente)->orderBy('codice')->get();
		$codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
		$fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agents->first()->codice)); //$agents->pluck('codice')->toArray();
		$fltAgents = AgentFltUtils::checkSpecialRules($fltAgents);

		$defaultDocFilter = new DocFilters();
		$defaultDocFilter->addArrayFilter('prGroupIncl', ['A', 'B', 'D0']);
		$defaultDocFilter->addArrayFilter('prGroupExcl', ['Z']);
		$defaultDocFilter->addArrayFilter('agente', $fltAgents);
		$defaultDocFilter->addBoolFilter('u_artlis&u_perslis', 1);
		$defaultDocFilter->addBoolFilter('ommerce', 0);
		$defaultDocFilter->addBoolFilter('filiali', 0);
		$defaultDocFilter->addStringFilter('codicearti', 'notEql', '');

		$ordUtils = (new DocRowUtils($defaultDocFilter));
		$ord = $ordUtils->getOrderToShip($this->dEndMonth);
		$ddtUtils = (new DocRowUtils($defaultDocFilter));
		$ddt = $ddtUtils->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$fattUtils = (new DocRowUtils($defaultDocFilter));
		$fatt = $fattUtils->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);

		$portfolio = (DocRowUtils::buildDocsPortfolio($ord, $ddt, $fatt))->sortByDesc('totNetVal.F');
		// dd($portfolio['C05900']);

		$title = "Portafoglio Clienti";
		$subTitle = "";
		$view = '_exports.pdf.portfolioCliPdf';
		$data = [
			'agents' => $agents,
			'mese' => $mese,
			'cumulativo' => $req->input('cumulativo'),
			'thisYear' => $this->thisYear,
			'prevYear' => $this->prevYear,
			'fltAgents' => $fltAgents,
			'portfolio' => $portfolio,
			'ordFilter' => $ordUtils->getDocFilter()->toString(),
			'ddtFilter' => $ddtUtils->getDocFilter()->toString(),
			'fatfFilter' => $fattUtils->getDocFilter()->toString(),
		];
		$pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

		return $pdf->stream($title . '-' . $subTitle . '.pdf');
	}

	public function portfolioCliDocPDF(Request $req, $codAg = null)
	{
		// Costruisco i filtri
		$this->thisYear = (!$req->input('year') ? Carbon::now()->year : $req->input('year'));
		$this->prevYear = $this->thisYear - 1;
		$this->dStartMonth = new Carbon('first day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
		$this->dEndMonth = new Carbon('last day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
		$mese = (!$req->input('mese') ? Carbon::now()->month : $req->input('mese'));
		if ($mese) {
			$this->dStartMonth = new Carbon('first day of ' . Carbon::createFromDate(null, $mese, null)->format('F') . ' ' . ((string)$this->thisYear));
			$this->dEndMonth = new Carbon('last day of ' . Carbon::createFromDate(null, $mese, null)->format('F') . ' ' . ((string)$this->thisYear));
		}
		if ($req->input('cumulativo')) {
			$this->dStartMonth = new Carbon('first day of january ' . ((string)$this->thisYear));
		}

		$dataFineAgente = Carbon::createFromDate($this->prevYear, 1, 1);
		$agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', $dataFineAgente)->orderBy('codice')->get();
		$codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
		$fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agents->first()->codice)); //$agents->pluck('codice')->toArray();
		$fltAgents = AgentFltUtils::checkSpecialRules($fltAgents);

		$defaultDocFilter = new DocFilters();
		$defaultDocFilter->addArrayFilter('prGroupIncl', ['A', 'B', 'D0']);
		$defaultDocFilter->addArrayFilter('prGroupExcl', ['Z']);
		$defaultDocFilter->addArrayFilter('agente', $fltAgents);
		$defaultDocFilter->addBoolFilter('u_artlis&u_perslis', 1);
		$defaultDocFilter->addBoolFilter('ommerce', 0);
		$defaultDocFilter->addBoolFilter('filiali', 0);
		$defaultDocFilter->addStringFilter('codicearti', 'notEql', '');

		$ordUtils = (new DocRowUtils($defaultDocFilter));
		$ord = $ordUtils->getOrderToShip($this->dEndMonth);
		$ddtUtils = (new DocRowUtils($defaultDocFilter));
		$ddt = $ddtUtils->getDdtNotInvoiced($this->thisYear, $this->dStartMonth, $this->dEndMonth);
		$fattUtils = (new DocRowUtils($defaultDocFilter));
		$fatt = $fattUtils->getInvoice($this->thisYear, $this->dStartMonth, $this->dEndMonth);

		$portfolio = DocRowUtils::buildDocsPortfolio($ord, $ddt, $fatt);
		// dd($portfolio['C05900']);

		$title = "Portafoglio Lista Documenti Clienti";
		$subTitle = "";
		$view = '_exports.pdf.portfolioCliDocPdf';
		$data = [
			'agents' => $agents,
			'mese' => $mese,
			'cumulativo' => $req->input('cumulativo'),
			'thisYear' => $this->thisYear,
			'prevYear' => $this->prevYear,
			'fltAgents' => $fltAgents,
			'portfolio' => $portfolio,
			'ordFilter' => $ordUtils->getDocFilter()->toString(),
			'ddtFilter' => $ddtUtils->getDocFilter()->toString(),
			'fatfFilter' => $fattUtils->getDocFilter()->toString(),
		];
		$pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

		return $pdf->stream($title . '-' . $subTitle . '.pdf');
	}

	public function portfolioListOCandXC(Request $req, $codAg = null)
	{
		// Costruisco i filtri
		$this->thisYear = (!$req->input('year') ? Carbon::now()->year : $req->input('year'));
		$this->prevYear = $this->thisYear - 1;
		$this->dStartMonth = new Carbon('first day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
		$this->dEndMonth = new Carbon('last day of ' . Carbon::now()->format('F') . ' ' . ((string)$this->thisYear));
		$mese = (!$req->input('mese') ? Carbon::now()->month : $req->input('mese'));
		if ($mese) {
			$this->dStartMonth = new Carbon('first day of ' . Carbon::createFromDate(null, $mese, null)->format('F') . ' ' . ((string)$this->thisYear));
			$this->dEndMonth = new Carbon('last day of ' . Carbon::createFromDate(null, $mese, null)->format('F') . ' ' . ((string)$this->thisYear));
		}
		if ($req->input('cumulativo')) {
			$this->dStartMonth = new Carbon('first day of january ' . ((string)$this->thisYear));
		}

		$dataFineAgente = Carbon::createFromDate($this->prevYear, 1, 1);
		$agents = Agent::select('codice', 'descrizion', 'u_dataini')->whereNull('u_dataini')->orWhere('u_dataini', '>=', $dataFineAgente)->orderBy('codice')->get();
		$codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
		$fltAgents = (!empty($codAg)) ? $codAg : array_wrap((!empty(RedisUser::get('codag')) ? RedisUser::get('codag') : $agents->first()->codice)); //$agents->pluck('codice')->toArray();
		$fltAgents = AgentFltUtils::checkSpecialRules($fltAgents);

		// $listOC = $this->getListDoc(['OC'], $fltAgents)->groupBy('agente');
		// $listXC = $this->getListDoc(['XC'], $fltAgents)->groupBy('agente');


		$defaultDocFilter = new DocFilters();
		$defaultDocFilter->addArrayFilter('prGroupIncl', ['A', 'B', 'D0']);
		$defaultDocFilter->addArrayFilter('prGroupExcl', ['Z']);
		$defaultDocFilter->addArrayFilter('agente', $fltAgents);
		$defaultDocFilter->addBoolFilter('u_artlis&u_perslis', 1);
		$defaultDocFilter->addBoolFilter('ommerce', 0);
		$defaultDocFilter->addBoolFilter('filiali', 0);
		$defaultDocFilter->addStringFilter('codicearti', 'notEql', '');
		$defaultDocFilter->addNumFilter('quantitare', 'plus', 0);
		$defaultDocFilter->addDateFilter('dataconseg', 'before', $this->dEndMonth);

		$OCFilter = clone $defaultDocFilter;
		$OCFilter->addArrayFilter('tipodoc', 'OC');
		$XCFilter = clone $defaultDocFilter;
		$XCFilter->addArrayFilter('tipodoc', 'XC');

		$listOC = (new DocRowUtils($OCFilter))->getDocs();
		$listOC = DocRowUtils::collectByAgentTipoModulo($listOC);
		$listXC = (new DocRowUtils($XCFilter))->getDocs();
		$listXC = DocRowUtils::collectByAgentTipoModulo($listXC);

		// dd($listOC);

		$title = "Portafoglio";
		$subTitle = "Dettaglio Documenti";
		$view = '_exports.pdf.portfolioDocPdf';
		$data = [
			'agents' => $agents,
			'mese' => $mese,
			'cumulativo' => $req->input('cumulativo'),
			'thisYear' => $this->thisYear,
			'prevYear' => $this->prevYear,
			'fltAgents' => $fltAgents,
			'listOC' => $listOC,
			'listXC' => $listXC,
			'xcFilter' => $XCFilter->toString(),
			'ocFilter' => $OCFilter->toString(),
		];
		$pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);

		return $pdf->stream($title . '-' . $subTitle . '.pdf');
	}
	
}