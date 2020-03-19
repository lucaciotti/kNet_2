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
        return AuditRisposteRighe::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $article = AuditRisposteRighe::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = AuditRisposteRighe::findOrFail($id);
        $article->delete();

        return 204;
    }
}
