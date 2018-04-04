@extends('layouts.app')

@section('htmlheader_title')
    - ToDo List
@endsection

@section('contentheader_title')
    #ToDo List
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('home') !!}
@endsection

@section('main-content')
  <div class="row">
    <div class="container">
      <div class="col-lg-12">
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title" data-widget="collapse">Lista delle Funzioni da Ultimare</h3>
          </div>
          <div class="box-body">
            <h3>
              <ul>
               <li>Export PDF & Excel</li>
               <br>
               <li>Rubrica dei Contatti</li>
               <br>
               <li>Inserimento Pre-Ordini da Web</li>
               <br>
               <li>Funzioni Email Integrate</li>
               <br>
               <li><u>STATISTICHE</u>
                 <ol>
                   <li>ABC Clienti</li>
                   <li>ABC Articoli su Cliente</li>
                 </ol>
               </li>
               <br>
             </ul>
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
