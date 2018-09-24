<?php

// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push(trans('_breadcrumbs.home'), url('/'));
});

// Home > About
Breadcrumbs::register('about', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('About', route('about'));
});

// Home > Client
Breadcrumbs::register('clients', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('_breadcrumbs.clients'), route('client::list'));
});

// Home > Client > [Client]
Breadcrumbs::register('client', function($breadcrumbs, $codClient)
{
    $breadcrumbs->parent('clients');
    $breadcrumbs->push($codClient, route('client::detail', $codClient));
});

// Home > Client > [Client] > [TipoDocs]
Breadcrumbs::register('clientDocs', function($breadcrumbs, $codClient, $tipoDoc)
{
    $breadcrumbs->parent('client', $codClient);
    switch ($tipoDoc) {
      case 'O':
        $docPush = trans('_breadcrumbs.orders');
        break;
      case 'B':
        $docPush = trans('_breadcrumbs.ddt');
        break;
      case 'F':
        $docPush = trans('_breadcrumbs.invoice');
        break;
      case 'P':
        $docPush = trans('_breadcrumbs.quotes');
        break;
      case 'N':
        $docPush = trans('_breadcrumbs.notecredito');
        break;

      default:
        $docPush = trans('_breadcrumbs.documents');
        break;
    }
    $breadcrumbs->push($docPush, route('client::detail', $codClient));
});

// Home > Docs
Breadcrumbs::register('docs', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('_breadcrumbs.documents'), route('doc::list'));
});

// Home > Docs > [TipoDocs]
Breadcrumbs::register('docsTipo', function($breadcrumbs, $tipoDoc)
{
    $breadcrumbs->parent('docs');
    switch ($tipoDoc) {
      case 'O':
        $docPush = trans('_breadcrumbs.orders');
        break;
      case 'B':
        $docPush = trans('_breadcrumbs.ddt');
        break;
      case 'F':
        $docPush = trans('_breadcrumbs.invoice');
        break;
      case 'P':
        $docPush = trans('_breadcrumbs.quotes');
        break;
      case 'N':
        $docPush = trans('_breadcrumbs.notecredito');
        break;

      default:
        $docPush = trans('_breadcrumbs.allDocs');
        break;
    }
    $breadcrumbs->push($docPush, route('doc::list', $tipoDoc));
});

// Home > Docs > [TipoDocs] > Detail
Breadcrumbs::register('docsDetail', function($breadcrumbs, $head)
{
    $breadcrumbs->parent('docsTipo', $head->tipomodulo);
    $docPush = $head->tipodoc." ".$head->numerodoc;
    $breadcrumbs->push($docPush, route('doc::detail', $head->id));
});

// Home > Scads
Breadcrumbs::register('scads', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('_breadcrumbs.scads'), route('scad::list'));
});

// Home > Prods
Breadcrumbs::register('prods', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('_breadcrumbs.products'), route('prod::list'));
});

// Home > [Client] > StatCli
Breadcrumbs::register('clientStFat', function($breadcrumbs, $codClient)
{
    $breadcrumbs->parent('client', $codClient);
    $breadcrumbs->push(trans('_breadcrumbs.stFatt'), route('stFatt::idxCli', $codClient));
});

// Home > StatAgent
Breadcrumbs::register('agentStFat', function($breadcrumbs, $codAg)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('_breadcrumbs.stFattAgent'), route('stFatt::idxAg', $codAg));
});

// Home > [Client] > ShowVisite
Breadcrumbs::register('visitShwCli', function($breadcrumbs, $codClient)
{
    $breadcrumbs->parent('client', $codClient);
    $breadcrumbs->push(trans('_breadcrumbs.event'), route('visit::show', $codClient));
});

// Home > [Client] > InsVisite
Breadcrumbs::register('visitInsCli', function($breadcrumbs, $codClient)
{
    $breadcrumbs->parent('client', $codClient);
    $breadcrumbs->push(trans('_breadcrumbs.insEvent'), route('visit::insert', $codClient));
});

// Home > InsVisite
Breadcrumbs::register('visitIns', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('_breadcrumbs.insEvent'), route('visit::insert'));
});
