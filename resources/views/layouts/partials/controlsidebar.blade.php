<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#control-sidebar-ditta-tab" data-toggle="tab"><i class="fa fa-globe"></i></a></li>
        <li class=""><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane active" id="control-sidebar-ditta-tab">
            <h3 class="control-sidebar-heading">{{ trans('_configMenu.dittaSelect') }}</h3>
            <form action="{{ route('user::changeDB') }}" method="post" class="control-sidebar-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="input-group">
                  {{-- <input type="text" name="q" class="form-control" placeholder="{{ trans('adminlte_lang::message.search') }}..."/> --}}
                  <select class="form-control" name="ditta">
                    <option value="it" @if (RedisUser::get('ditta_DB')=='kNet_it') selected="selected" @endif>kNet {{ trans('_configMenu.italia') }}</option>
                    <option value="es" @if (RedisUser::get('ditta_DB')=='kNet_es') selected="selected" @endif>kNet {{ trans('_configMenu.spagna') }}</option>
                    <option value="fr" @if (RedisUser::get('ditta_DB')=='kNet_fr') selected="selected" @endif>kNet {{ trans('_configMenu.francia') }}</option>
                  </select>
                  <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-angle-right"></i></button>
                  </span>
                </div>
            </form>
        </div><!-- /.tab-pane -->

        <!-- Settings tab content -->
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <h3 class="control-sidebar-heading"> {{ trans('_configMenu.extraSettings') }}</h3>
            <ul class='control-sidebar-menu'>
                <li>
                    <a href="{{route('user::users.index')}}">
                        <i class="menu-icon fa fa-users bg-info"></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ trans('_configMenu.usersManage') }}</h4>
                            <p>{{ trans('_configMenu.usersManageDesc') }}</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('user::usersCli')}}">
                        <i class="menu-icon fa fa-users bg-info"></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ trans('_configMenu.clientsManage') }}</h4>
                            <p>{{ trans('_configMenu.clientsManageDesc') }}</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('user::import')}}">
                        <i class="menu-icon fa fa-user-plus bg-yellow"></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ trans('_configMenu.importUsers') }}</h4>
                            <p>{{ trans('_configMenu.importUsersDesc') }}</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('rubri::import')}}">
                        <i class="menu-icon fa fa-user-plus bg-yellow"></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">Import Rubrica LEAD</h4>
                            <p>{{ trans('_configMenu.importUsersDesc') }}</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside><!-- /.control-sidebar

<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>
