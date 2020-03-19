<?php

namespace knet\Http\Controllers\Audit;

use Illuminate\Http\Request;
use knet\AuditModels\AuditRisposteRighe;
use knet\Http\Controllers\Controller;

class AuditRispRigheController extends Controller
{

    public function all($id)
    {
        return AuditRisposteRighe::where('id_testa', $id)->get();
    }

    public function show($id)
    {
        return AuditRisposteRighe::find($id);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $audit = AuditRisposteRighe::find($request->id);
        if ($audit) {
            $audit->update($request->all());
        } else {
            $audit = AuditRisposteRighe::create($request->all());
        }
        return $audit;
    }

    public function update(Request $request, $id)
    {
        $audit = AuditRisposteRighe::findOrFail($id);
        $audit->update($request->all());

        return $audit;
    }

    public function delete(Request $request, $id)
    {
        $audit = AuditRisposteRighe::findOrFail($id);
        $audit->delete();

        return 204;
    }
}
