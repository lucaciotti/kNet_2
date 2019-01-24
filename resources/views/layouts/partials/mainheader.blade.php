<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ url('/home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>k</b>Net</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>kNet</b> 2.0 </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">{{ trans('_message.togglenav') }}</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            
            <ul class="nav navbar-nav">
                @if (Auth::guest())
                    <li><a href="{{ url('/register') }}">{{ trans('_message.register') }}</a></li>
                    <li><a href="{{ url('/login') }}">{{ trans('_message.login') }}</a></li>
                @else
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="{{asset('/img/avatar_default.jpg')}}" class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="{{asset('/img/avatar_default.jpg')}}" class="img-circle" alt="User Image" />
                                <p>
                                    {{ Auth::user()->name }}
                                    {{-- <small>{{ trans('adminlte_lang::message.login') }}</small> --}}
                                    <small>{{ trans('_configMenu.ditta') }}: {{ RedisUser::get('ditta_DB') }}</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ route('user::users.show', Auth::user()->id ) }}" class="btn btn-default btn-flat">{{ trans('_message.profile') }}</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat" id="logout"
                                       onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        {{ trans('_message.signout') }}
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                        <input type="submit" value="logout" style="display: none;">
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (!in_array(RedisUser::get('role'), ['client', 'agent', 'superAgent', 'user']))
                  <!-- Control Sidebar Toggle Button -->
                  <li>
                      <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                  </li>
                @endif
            </ul>
        </div>
    </nav>
</header>
