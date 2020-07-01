<?php

namespace knet\Http\Controllers\Audit;

use Illuminate\Http\Request;
use knet\AuditModels\AuditDomande;
use knet\Http\Controllers\Controller;

class AuditDomandeController extends Controller
{
    public function all($codice)
    {
        return AuditDomande::where('codice_modello', $codice)->get();
    }

    public function show($id)
    {
        return AuditDomande::find($id);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $audit = AuditDomande::find($request->id);
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
            $audit = AuditDomande::create([
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
        $audit = AuditDomande::findOrFail($id);
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
        $audit = AuditDomande::findOrFail($id);
        $audit->delete();

        return 204;
    }
}
