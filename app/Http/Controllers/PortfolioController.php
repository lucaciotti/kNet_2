<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function idxAg(Request $req, $codAg=null){
        $agents = DocCli::distinct()->select('agente')
                          ->where('agente', '!=', '00')
                          ->where('agente', '!=', '')
                          ->with([
                            'agent' => function($query){
                              $query->select('codice', 'descrizion');
                            }
                            ])
                            ->get();
        $codAg = ($req->input('codag')) ? $req->input('codag') : $codAg;
        $agente = (!empty($codAg)) ? $codAg : $agents->first()->agente;
        $thisYear =  Carbon::now()->year;
        $prevYear = $thisYear-1;
        



    }
}


/* IF oApp.ditta == 'KRONA'
	IF !lEstero
		SELECT doctes.tipodoc,	;
			SUM(IIF(docTes.TipoModulo == 'N', -1, 1) * Round(ScontaDel(docrig.PrezzoUn, IIF(EMPTY(docrig.Sconti),"0", docrig.sconti)+"+"+IIF(EMPTY(doctes.Sconti),"0", doctes.sconti)+"+"+IIF(EMPTY(doctes.scontocass),"0", doctes.scontocass), Cambi.DecValore) / docrig.cambio * docrig.QuantitaRe, 2)) as totale ;
		FROM private!doctes INNER JOIN private!docRig ON docTes.ID = docRig.ID_Testa ;
					inner join private!anagrafe on anagrafe.codice == doctes.codicecf 	;
					INNER JOIN private!cambi ON cambi.codice == doctes.valuta	;
		WHERE 	!DocRig.OmMerce ;
				AND DocRig.QuantitaRe > 0 ;
				AND &cWhereData ;
				AND (DocRig.Gruppo = 'A' OR DocRig.Gruppo = 'B') ;
				AND  DocTes.CodiceCF IN ( SELECT Codice FROM Private!AnaGrafe WHERE !(Classe == '020'))  AND DocTes.Agente BETWEEN '002' AND '99 ' ;
				AND !(DocTes.Codicecf == 'C01253') ;
				AND (DocTes.Esercizio == cEsercizio OR DocTes.Esercizio == TRANSFORM(YEAR(thisform.datastampa)-1)) ;
				AND DocTes.TipoDoc == 'OC' ;
		GROUP BY doctes.tipodoc ;
		INTO CURSOR _DOCOC_ 
		makecursorwritable()
	ELSE
		SELECT doctes.tipodoc,	;
			SUM(IIF(docTes.TipoModulo == 'N', -1, 1) * Round(ScontaDel(docrig.PrezzoUn, IIF(EMPTY(docrig.Sconti),"0", docrig.sconti)+"+"+IIF(EMPTY(doctes.Sconti),"0", doctes.sconti)+"+"+IIF(EMPTY(doctes.scontocass),"0", doctes.scontocass), Cambi.DecValore) / docrig.cambio * docrig.QuantitaRe, 2)) as totale ;
		FROM private!doctes INNER JOIN private!docRig ON docTes.ID = docRig.ID_Testa ;
					inner join private!anagrafe on anagrafe.codice == doctes.codicecf 	;
					INNER JOIN private!cambi ON cambi.codice == doctes.valuta	;
		WHERE 	!DocRig.OmMerce ;
				AND DocRig.QuantitaRe > 0 ;
				AND &cWhereData ;
				AND (DocRig.Gruppo = 'A' OR DocRig.Gruppo = 'B') ;
				AND  DocTes.CodiceCF IN ( SELECT Codice FROM Private!AnaGrafe WHERE !(Classe == '020'))  AND LEFT(DocTes.Agente,1) == 'A' ;
				AND !(DocTes.Codicecf == 'C01253') ;
				AND (DocTes.Esercizio == cEsercizio OR DocTes.Esercizio == TRANSFORM(YEAR(thisform.datastampa)-1)) ;
				AND DocTes.TipoDoc == 'OC' ;
		GROUP BY doctes.tipodoc ;
		INTO CURSOR _DOCOC_ 
		makecursorwritable()
	ENDIF
ELSE
	SELECT doctes.tipodoc,	;
		SUM(IIF(docTes.TipoModulo == 'N', -1, 1) * Round(ScontaDel(docrig.PrezzoUn, IIF(EMPTY(docrig.Sconti),"0", docrig.sconti)+"+"+IIF(EMPTY(doctes.Sconti),"0", doctes.sconti)+"+"+IIF(EMPTY(doctes.scontocass),"0", doctes.scontocass), Cambi.DecValore) / docrig.cambio * docrig.QuantitaRe, 2)) as totale ;
	FROM private!doctes INNER JOIN private!docRig ON docTes.ID = docRig.ID_Testa ;
				inner join private!anagrafe on anagrafe.codice == doctes.codicecf 	;
				INNER JOIN private!cambi ON cambi.codice == doctes.valuta	;
	WHERE 	!DocRig.OmMerce ;
			AND DocRig.QuantitaRe > 0 ;
			AND &cWhereData ;
			AND (DocRig.Gruppo = 'A' OR DocRig.Gruppo = 'B') ;
				AND (DocTes.Esercizio == cEsercizio OR DocTes.Esercizio == TRANSFORM(YEAR(thisform.datastampa)-1)) ;
			AND DocTes.TipoDoc == 'OC' ;
		GROUP BY doctes.tipodoc ;
	INTO CURSOR _DOCOC_ 
	makecursorwritable()
ENDIF */


