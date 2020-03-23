<?php

namespace knet\Http\Controllers\Audit;

use Illuminate\Http\Request;
use knet\AuditModels\AuditModel;
use knet\Http\Controllers\Controller;

class AuditModelsController extends Controller
{
    public function all()
    {
        return AuditModel::all();
    }

    public function show($codice)
    {
        return AuditModel::find($codice);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $audit = AuditModel::find($request->codice);
        if ($audit) {
            $audit->update($request->all());
        } else {
            $audit = AuditModel::create($request->all());
        }
        return $audit;
    }

    public function update(Request $request, $codice)
    {
        $audit = AuditModel::findOrFail($codice);
        $audit->update($request->all());

        return $audit;
    }

    public function delete(Request $request, $codice)
    {
        $audit = AuditModel::findOrFail($codice);
        $audit->delete();

        return 204;
    }
}
