<?php

namespace knet\Http\Controllers\Audit\_Dev;

use Illuminate\Http\Request;
use knet\AuditModels\_Dev\AuditModel_Dev;
use knet\Http\Controllers\Controller;

class AuditModelsController_Dev extends Controller
{
    public function all()
    {
        return AuditModel_Dev::all();
    }

    public function show($codice)
    {
        return AuditModel_Dev::find($codice);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $audit = AuditModel_Dev::find($request->codice);
        if ($audit) {
            $audit->update($request->all());
        } else {
            $audit = AuditModel_Dev::create($request->all());
        }
        return $audit;
    }

    public function update(Request $request, $codice)
    {
        $audit = AuditModel_Dev::findOrFail($codice);
        $audit->update($request->all());

        return $audit;
    }

    public function delete(Request $request, $codice)
    {
        $audit = AuditModel_Dev::findOrFail($codice);
        $audit->delete();

        return 204;
    }
}
