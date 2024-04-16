@extends('layouts.app')

@section('htmlheader_title')
- {{ trans('user.headTitle_edt') }}
@endsection

@section('contentheader_title')
@endsection

@section('contentheader_breadcrumb')
{!! Breadcrumbs::render('humanresource') !!}
@endsection

@section('main-content')
    <div class="row">
        <div class="col-lg-3">
            <form action="{{ route('user::events') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title" data-widget="collapse">Seleziona Utente</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>Utente</label>
                            <select name="selectedUser" class="form-control select2 selectAll"
                                data-placeholder="Select User" style="width: 100%;">
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if(isset($selectedUser) && $user->id==$selectedUser)
                                    selected
                                    @endif
                                    >{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
        
                <div class="box box-default">
                    <div class="box-body">
                        <button type="submit" class="btn btn-primary btn-block">{{ trans('_message.submit') }}</button>
                    </div>
                </div>
        
            </form>
        </div>
        {{-- <div class="col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h4 class="box-title">Draggable Events</h4>
                </div>
                <div class="box-body">
    
                    <div id="external-events">
                        <div class="external-event bg-green ui-draggable ui-draggable-handle" style="position: relative;">
                            Lunch</div>
                        <div class="external-event bg-yellow ui-draggable ui-draggable-handle" style="position: relative;">
                            Go home</div>
                        <div class="external-event bg-aqua ui-draggable ui-draggable-handle" style="position: relative;">Do
                            homework</div>
                        <div class="external-event bg-light-blue ui-draggable ui-draggable-handle"
                            style="position: relative;">Work on UI design</div>
                        <div class="external-event bg-red ui-draggable ui-draggable-handle" style="position: relative;">
                            Sleep tight</div>
                        <div class="checkbox">
                            <label for="drop-remove">
                                <input type="checkbox" id="drop-remove">
                                remove after drop
                            </label>
                        </div>
                    </div>
                </div>
    
            </div>
    
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Create Event</h3>
                </div>
                <div class="box-body">
                    <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
    
                        <ul class="fc-color-picker" id="color-chooser">
                            <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                            <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                        </ul>
                    </div>
    
                    <div class="input-group">
                        <input id="new-event" type="text" class="form-control" placeholder="Event Title">
                        <div class="input-group-btn">
                            <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Add</button>
                        </div>
    
                    </div>
    
                </div>
            </div>
        </div> --}}
    
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body no-padding">
    
                    <div id="calendar" class="fc fc-unthemed fc-ltr">

                    </div>
                </div>
    
            </div>
    
        </div>
    
    </div>
@endsection

@section('extra_script')
@include('layouts.partials.scripts.fullCalendar')

@endsection