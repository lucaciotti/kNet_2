<?php

namespace knet\Http\Controllers\Audit\_Dev;

use Illuminate\Http\Request;
use knet\AuditModels\_Dev\AuditDomande_Dev;
use knet\Http\Controllers\Controller;

class AuditDomandeController_Dev extends Controller
{
    public function all($codice)
    {
        return AuditDomande_Dev::where('codice_modello', $codice)->get();
    }

    public function show($id)
    {
        return AuditDomande_Dev::find($id);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $audit = AuditDomande_Dev::find($request->id);
        if ($audit) {
            $audit->update([
                'codice_modello' => ($request->codice_modello) ? $request->codice_modello : '',
                'super_capitolo' => ($request->super_capitolo) ? $request->super_capitolo : '',
                'capitolo' => ($request->capitolo) ? $request->capitolo : '',
                'sub_capitolo' => ($request->sub_capitolo) ? $request->sub_capitolo : '',
                'domanda' => ($request->domanda) ? $request->domanda : '',
                'descrizione' => ($request->descrizione) ? $request->descrizione : ''
            ]);
        } else {
            // $audit = AuditDomande::create($request->all()); html_entity_decode($request->descrizione, ENT_QUOTES) : ''
            $audit = AuditDomande_Dev::create([
                'id' => $request->id, 
                'codice_modello' => ($request->codice_modello) ? $request->codice_modello : '',
                'super_capitolo' => ($request->super_capitolo) ? $request->super_capitolo : '',
                'capitolo' => ($request->capitolo) ? $request->capitolo : '',
                'sub_capitolo' => ($request->sub_capitolo) ? $request->sub_capitolo : '',
                'domanda' => ($request->domanda) ? $request->domanda : '',
                'descrizione' => ($request->descrizione) ? $request->descrizione : ''
            ]);
        }
        return $audit;
    }

    public function update(Request $request, $id)
    {
        $audit = AuditDomande_Dev::findOrFail($id);
        // $audit->update($request->all());
        $audit->update([
            'codice_modello' => ($request->codice_modello) ? $request->codice_modello : '',
            'super_capitolo' => ($request->super_capitolo) ? $request->super_capitolo : '',
            'capitolo' => ($request->capitolo) ? $request->capitolo : '',
            'sub_capitolo' => ($request->sub_capitolo) ? $request->sub_capitolo : '',
            'domanda' => ($request->domanda) ? $request->domanda : '',
            'descrizione' => ($request->descrizione) ? $request->descrizione : ''
        ]);

        return $audit;
    }

    public function delete(Request $request, $id)
    {
        $audit = AuditDomande_Dev::findOrFail($id);
        $audit->delete();

        return 204;
    }

    public function deleteForModello(Request $request, $codice_modello)
    {
        $auditList = AuditDomande_Dev::where('codice_modello', $codice_modello)->delete();
        return 204;
    }

    public function deleteAll(Request $request)
    {
        $auditList = AuditDomande_Dev::truncate();
        return 204;
    }
}
