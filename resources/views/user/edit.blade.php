@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('user.headTitle_edt') }}
@endsection

@section('contentheader_title')
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('clients') !!}
@endsection

@section('main-content')
  <div class="row">
    <div class="col-lg-10 col-lg-offset-1">
      <img src="{{asset('/img/avatar_default.jpg')}}" style="width:120px; height:120px; float:left; border-radius:50%; margin-right:25px;"/>
      <h2>{{ trans('user.userProfile', ['user' => $user->name]) }}</h2>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-lg-8 col-lg-offset-2">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('user.modifyUser') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">

          <form action="{{ route('user::users.update', $user->id) }}" method="POST">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
            <div class="form-group">
              <label>{{ trans('user.name') }}</label>
              <input type="text" class="form-control" name="name" value="{{$user->name}}">
            </div>
            <div class="form-group">
              <label>{{ trans('user.nickname') }}</label>
              <input type="text" class="form-control" name="nickname" value="{{$user->nickname}}" readonly="readonly">
            </div>
            <div class="form-group">
              <label>{{ trans('user.eMail') }}</label>
              <input type="text" class="form-control" name="email" value="{{$user->email}}" required>
            </div>
            <div class="form-group">
              <label>{{ trans('user.role') }}</label>
              <select name="role" class="form-control select2" style="width: 100%;">
                <option value=""> </option>
                @foreach ($roles as $role)
                  <option value="{{ $role->id }}"
                    @if($user->roles->contains($role->id))
                        selected
                    @endif
                    >{{ $role->display_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>{{ trans('user.codAg') }}</label>
              <select name="codag" class="form-control select2" style="width: 100%;">
                <option value=""> </option>
                @foreach ($agents as $agent)
                  <option value="{{ $agent->codice }}"
                    @if($agent->codice===$user->codag)
                        selected
                    @endif
                    >[{{$agent->codice}}] {{ $agent->descrizion }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>{{ trans('user.codCli') }}</label>
              <select name="codcli" class="form-control select2" style="width: 100%;">
                <option value=""> </option>
                @foreach ($clients as $client)
                  <option value="{{ $client->codice }}"
                    @if($client->codice==$user->codcli)
                        selected
                    @endif
                    >[{{$client->codice}}] {{ $client->descrizion }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>{{ trans('user.refDitta') }}</label>
              @if (RedisUser::get('role')=='admin')
                <select name="ditta" class="form-control" style="width: 100%;">
                  <option value="it" @if ($user->ditta=='it') selected="selected" @endif>kNet Italia</option>
                  <option value="es" @if ($user->ditta=='es') selected="selected" @endif>kNet Spagna</option>
                  <option value="fr" @if ($user->ditta=='fr') selected="selected" @endif>kNet Francia</option>
                </select>
              @else
                <input type="text" class="form-control" name="ditta" value="kNet_{{$user->ditta}}" readonly="readonly">
              @endif

            </div>

            <div class="form-group">
              <label>isActive?</label>
              <div class="radio">
                <label>
                  <input type="radio" name="isActive" id="opt1" value="0" @if(!$user->isActive) checked @endif> No
                </label>
                <label>
                  <input type="radio" name="isActive" id="opt2" value="1" @if($user->isActive) checked @endif>Si
                </label>
              </div>
            </div>

            <div>
              <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra_script')
  @include('layouts.partials.scripts.iCheck')
  @include('layouts.partials.scripts.select2')
  @include('layouts.partials.scripts.datePicker')
@endsection
