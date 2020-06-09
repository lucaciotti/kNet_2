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
            $audit->update([
                'risposta' => ($request->risposta) ? $request->risposta : 0,
                'osservazioni' => ($request->osservazioni) ? $request->osservazioni : '',
                'note' => ($request->note) ? $request->note : '',
                'tablet_id' => ($request->tablet_id) ? $request->tablet_id : '0'
            ]);
        } else {
            // $audit = AuditRisposteRighe::create($request->all());
            $audit = AuditRisposteRighe::create([
                'id' => $request->id,
                'id_testa' => $request->id_testa,
                'id_domanda' => $request->id_domanda,
                'risposta' => ($request->risposta) ? $request->risposta : 0,
                'osservazioni' => ($request->osservazioni) ? $request->osservazioni : '',
                'note' => ($request->note) ? $request->note : '',
                'tablet_id' => ($request->tablet_id) ? $request->tablet_id : '0'
            ]);
        }
        return $audit;
    }

    public function update(Request $request, $id)
    {
        $audit = AuditRisposteRighe::findOrFail($id);
        // $audit->update($request->all());
        $audit->update([
            'risposta' => ($request->risposta) ? $request->risposta : 0,
            'osservazioni' => ($request->osservazioni) ? $request->osservazioni : '',
            'note' => ($request->note) ? $request->note : '',
            'tablet_id' => ($request->tablet_id) ? $request->tablet_id : '0'
        ]);

        return $audit;
    }

    public function delete(Request $request, $id)
    {
        $audit = AuditRisposteRighe::findOrFail($id);
        $audit->delete();

        return 204;
    }
}
