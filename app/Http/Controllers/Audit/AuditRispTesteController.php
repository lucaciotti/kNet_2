<?php

namespace knet\Http\Controllers\Audit;

use Illuminate\Http\Request;
use knet\AuditModels\AuditRisposteTeste;
use knet\Http\Controllers\Controller;

class AuditRispTesteController extends Controller
{
    public function all()
    {
        return AuditRisposteTeste::all();
    }

    public function show($id)
    {
        return AuditRisposteTeste::find($id);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $audit = AuditRisposteTeste::find($request->idweb);
        if($audit){
            $audit->update([
                'codice_modello' => $request->codice_modello,
                'azienda' => $request->azienda,
                'data' => $request->data,
                'auditor' => ($request->auditor) ? $request->auditor : '',
                'persone_intervistate' => ($request->persone_intervistate) ? $request->persone_intervistate : '',
                'tablet_id' => ($request->id) ? $request->id : 0,
                'conclusioni' => ($request->conclusioni) ? $request->conclusioni : ''
            ]);
        } else {
            // $audit = AuditRisposteTeste::create($request->all());
            $audit = AuditRisposteTeste::create([
                'codice_modello' => $request->codice_modello,
                'azienda' => $request->azienda,
                'data' => $request->data,
                'auditor' => ($request->auditor) ? $request->auditor : '',
                'persone_intervistate' => ($request->persone_intervistate) ? $request->persone_intervistate : '',
                'tablet_id' => ($request->id) ? $request->id : 0,
                'conclusioni' => ($request->conclusioni) ? $request->conclusioni : ''
            ]);
        }
        return $audit->id;
    }

    public function update(Request $request, $id)
    {
        $audit = AuditRisposteTeste::findOrFail($id);
        // $audit->update($request->all());
        $audit->update([
            'codice_modello' => $request->codice_modello,
            'azienda' => $request->azienda,
            'data' => $request->data,
            'auditor' => ($request->auditor) ? $request->auditor : '',
            'persone_intervistate' => ($request->persone_intervistate) ? $request->persone_intervistate : '',
            'tablet_id' => ($request->id) ? $request->id : 0,
            'conclusioni' => ($request->conclusioni) ? $request->conclusioni : ''
        ]);

        return $audit->id;
    }

    public function delete(Request $request, $id)
    {
        $audit = AuditRisposteTeste::findOrFail($id);
        $audit->delete();

        return 204;
    }
}
