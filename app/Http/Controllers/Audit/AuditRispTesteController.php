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
        $audit = AuditRisposteTeste::find($request->id);
        if($audit){
            $audit->update($request->all());
        } else {
            $audit = AuditRisposteTeste::create($request->all());
        }
        return $audit;
    }

    public function update(Request $request, $id)
    {
        $audit = AuditRisposteTeste::findOrFail($id);
        $audit->update($request->all());

        return $audit;
    }

    public function delete(Request $request, $id)
    {
        $audit = AuditRisposteTeste::findOrFail($id);
        $audit->delete();

        return 204;
    }
}
