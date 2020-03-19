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
        return AuditRisposteTeste::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $article = AuditRisposteTeste::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = AuditRisposteTeste::findOrFail($id);
        $article->delete();

        return 204;
    }
}
