<?php

namespace knet\Http\Controllers\Audit\_Dev;

use Illuminate\Http\Request;
use knet\AuditModels\_Dev\AuditRisposteRighe_Dev;
use knet\AuditModels\_Dev\AuditRisposteTeste_Dev;
use knet\Http\Controllers\Controller;

class AuditRispRigheController_Dev extends Controller
{

    public function all($id)
    {
        return AuditRisposteRighe_Dev::where('id_testa', $id)->get();
    }

    public function show($id)
    {
        return AuditRisposteRighe_Dev::find($id);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $audit = AuditRisposteRighe_Dev::find($request->idweb);
        if ($audit) {
            $audit->update([
                'risposta' => ($request->risposta) ? $request->risposta : 0,
                'osservazioni' => ($request->osservazioni) ? htmlentities($request->osservazioni) : '',
                'note' => ($request->note) ? htmlentities($request->note) : '',
                'voto' => ($request->voto) ? $request->voto : 0,
                'tablet_id' => ($request->id) ? $request->id : 0,
                'tablet_idtesta' => ($request->id_testa) ? $request->id_testa : 0
            ]);
        } else {
            // $audit = AuditRisposteRighe::create($request->all());
            $auditTes = AuditRisposteTeste_Dev::where('tablet_id', $request->id_testa)->first();
            $audit = AuditRisposteRighe_Dev::create([
                'id_testa' => $auditTes->id,
                'id_domanda' => $request->id_domanda,
                'risposta' => ($request->risposta) ? $request->risposta : 0,
                'osservazioni' => ($request->osservazioni) ? htmlentities($request->osservazioni) : '',
                'note' => ($request->note) ? htmlentities($request->note) : '',
                'voto' => ($request->voto) ? $request->voto : 0,
                'tablet_id' => ($request->id) ? $request->id : 0,
                'tablet_idtesta' => ($request->id_testa) ? $request->id_testa : 0
            ]);
        }
        return $audit->id;
    }

    public function update(Request $request, $id)
    {
        $audit = AuditRisposteRighe_Dev::findOrFail($id);
        // $audit->update($request->all());
        $audit->update([
            'risposta' => ($request->risposta) ? $request->risposta : 0,
            'osservazioni' => ($request->osservazioni) ? htmlentities($request->osservazioni) : '',
            'note' => ($request->note) ? htmlentities($request->note) : '',
            'voto' => ($request->voto) ? $request->voto : 0,
            'tablet_id' => ($request->id) ? $request->id : 0,
            'tablet_idtesta' => ($request->id_testa) ? $request->id_testa : 0
        ]);

        return $audit->id;
    }

    public function delete(Request $request, $id)
    {
        $audit = AuditRisposteRighe_Dev::findOrFail($id);
        $audit->delete();

        return 204;
    }
}
