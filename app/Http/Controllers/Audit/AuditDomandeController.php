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
            $audit->update($request->all());
        } else {
            $audit = AuditDomande::create($request->all());
        }
        return $audit;
    }

    public function update(Request $request, $id)
    {
        $audit = AuditDomande::findOrFail($id);
        $audit->update($request->all());

        return $audit;
    }

    public function delete(Request $request, $id)
    {
        $audit = AuditDomande::findOrFail($id);
        $audit->delete();

        return 204;
    }
}
