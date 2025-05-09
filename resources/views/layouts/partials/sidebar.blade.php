<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{asset('/img/avatar_default.jpg')}}" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('_menu.online') }}</a>
                </div>
            </div>
        @endif

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{ trans('_menu.search') }}..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="header">Arca Web</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="{{ Ekko::isActiveURL('home') }}"><a href="{{ url('/home') }}"><i class='fa fa-home'></i> <span>{{ trans('_menu.home') }}</span></a></li>

            @if (!in_array(RedisUser::get('role'), ['user']))
              @if (!in_array(RedisUser::get('role'), ['quality']))
                @if (in_array(RedisUser::get('role'), ['client']))
                  <li class="{{ Ekko::isActiveRoute('client::*') }}"><a href="{{ route('client::list') }}"><i class='fa fa-user'></i> <span>{{ trans('_menu.anagClient') }}</span></a></li>
                @else
                  <li class="{{ Ekko::isActiveRoute('client::*') }}"><a href="{{ route('client::list') }}"><i class='fa fa-users'></i> <span>{{ trans('_menu.listClients') }}</span></a></li>
                @endif 
              @endif             
              @if (in_array(RedisUser::get('role'), ['admin', 'direz', 'supplier', 'quality' ]))
                @if (in_array(RedisUser::get('role'), ['supplier']))
                  <li class="{{ Ekko::isActiveRoute('supplier::*') }}"><a href="{{ route('supplier::list') }}"><i class='fa fa-user'></i> <span>{{ trans('_menu.anagSupplier') }}</span></a></li>
                @else
                  <li class="{{ Ekko::isActiveRoute('supplier::*') }}"><a href="{{ route('supplier::list') }}"><i class='fa fa-address-book'></i> <span>{{ trans('_menu.listSupplier') }}</span></a></li>
                @endif
              @endif
              @if (!in_array(RedisUser::get('role'), ['quality']))
                <li class="treeview {{ Ekko::isActiveRoute('doc::*') }}">
                    <a href="{{ route('doc::list') }}"><i class='fa fa-copy'></i> <span>{{ trans('_menu.documents') }}</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li class="{{ Ekko::isActiveRoute('doc::list','O') }}"><a href="{{ route('doc::list', 'O') }}">{{ strtoupper(trans('_menu.orders')) }}</a></li>
                        <li class="{{ Ekko::isActiveRoute('doc::list','B') }}"><a href="{{ route('doc::list', 'B') }}">{{ strtoupper(trans('_menu.ddt')) }}</a></li>
                        <li class="{{ Ekko::isActiveRoute('doc::list','F') }}"><a href="{{ route('doc::list', 'F') }}">{{ strtoupper(trans('_menu.invoice')) }}</a></li>
                    </ul>
                </li>
                @if (!in_array(RedisUser::get('role'), ['client']))
                  <li class="treeview {{ Ekko::isActiveRoute('listini::*') }}">
                      <a href="{{ route('listini::idxCli') }}"><i class='fa fa-list-ul'></i> <span>Listini</span> <i class="fa fa-angle-left pull-right"></i></a>
                      <ul class="treeview-menu">
                          <li class="{{ Ekko::isActiveRoute('listini::idxCli') }}"><a href="{{ route('listini::idxCli') }}">Cliente</a></li>
                          <li class="{{ Ekko::isActiveRoute('listini::grpCli') }}"><a href="{{ route('listini::grpCli') }}">Gruppo Clienti</a></li>
                          <li>&nbsp;</li>
                          @if (!in_array(RedisUser::get('role'), ['agent', 'client', 'marketing', 'tecnical_sales']))
                              <li class="{{ Ekko::isActiveRoute('listini::grpCli') }}"><a href="{{ route('listini::cliListScad') }}">Listino Clienti in Scadenza</a></li>
                              <li class="{{ Ekko::isActiveRoute('listini::grpCli') }}"><a href="{{ route('listini::grpListScad') }}">Listino Gruppi in Scadenza</a></li>
                          @endif
                          <li>&nbsp;</li>
                          {{-- @if (!in_array(RedisUser::get('role'), ['agent', 'client']))
                          <li class="{{ Ekko::isActiveRoute('promo::idx') }}"><a href="{{ route('promo::idx') }}">Promo Attive</a></li>
                          @endif --}}
                      </ul>
                  </li>
                @endif
                @if (!in_array(RedisUser::get('role'), ['marketing', 'tecnical_sales']))
                <li class="{{ Ekko::isActiveRoute('scad::*') }}"><a href="{{ route('scad::list') }}"><i class='fa fa-money'></i> <span>{{ trans('_menu.payment') }}</span></a></li>
                @endif
                
                @if (!in_array(RedisUser::get('role'), ['client']))
                <li class="{{ Ekko::isActiveRoute('rnc::*') }}"><a href="{{ route('rnc::list') }}"><i class='fa fa-wrench'></i> <span>{{ trans('_menu.rnc') }}</span></a></li>
                @endif
              @endif
              {{-- <li class="{{ Ekko::isActiveRoute('prod::*') }}"><a href="{{ route('prod::list') }}"><i class='fa fa-shopping-bag'></i> <span>{{ trans('_menu.products') }}</span></a></li> --}}
              <li><i class='fa fa-empty'></i></li>

              {{-- @if (!Auth::user()->hasRole('client')) --}}
              @if (!in_array(RedisUser::get('role'), ['client']))
                <li class="header">Funzioni Web</li>
                {{-- @if (RedisUser::get('ditta_DB')=='kNet_it') --}}
                  <li class=""><a href="{{ route('rubri::list') }}"><i class="fa fa-address-card-o"></i> <span>Rubrica Contatti</span></a></li>
                {{-- @endif --}}
                {{-- <li class=""><a href="{{ route('doc::list', 'O') }}"><i class='fa fa-pencil-square-o'></i> <span>Pre-Ordini via Web</span></a></li> --}}              
                <li class="treeview {{ Ekko::isActiveRoute('visit::*') }}">
                  <a href="{{ route('visit::insert') }}"><i class='fa fa-weixin'></i> <span>Visite & Eventi</span><i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                      @if (!in_array(RedisUser::get('role'), ['quality']))
                        <li class="{{ Ekko::isActiveRoute('visit::*') }}"><a href="{{ route('visit::insert') }}"> <span>{{ trans('_menu.insVisits') }}</span></a></li>
                      @endif
                      @if (in_array(RedisUser::get('role'), ['admin', 'direz', 'quality' ]))
                          <li class="{{ Ekko::isActiveRoute('visit::*') }}"><a href="{{ route('visit::insertSupplier') }}"> <span>{{ trans('_menu.insVisitsSupp') }}</span></a></li>
                      @endif
                      <li class="{{ Ekko::isActiveRoute('visit::*') }}"><a href="{{ route('visit::report') }}"> <span>{{ trans('_menu.reportVisits') }}</span></a></li>
                      {{-- @if (RedisUser::get('ditta_DB')=='kNet_it')
                          <li class="{{ Ekko::isActiveRoute('visit::*') }}"><a href="{{ route('visit::insertRubri') }}"> <span>Inserimento Visita Contatto</span></a></li>    
                      @endif --}}
                  </ul>
                </li>
                {{-- @if (in_array(RedisUser::get('ditta_DB'), ['kNet_it'])) --}}
                  {{-- <li class=""><a href="http://kalendar.kronakoblenz.it:8080/" target="_blank"><i class='fa fa-calendar'></i> <span>Kalendar</span></a></li>
                  <li class=""><a href="http://drive.k-group.com/" target="_blank"><i class='fa fa-server'></i> <span>Drive Space</span></a></li> --}}
                {{-- @endif --}}
                @if (!in_array(RedisUser::get('role'), ['quality']))
                  <li><i class='fa fa-empty'></i></li>

                  <li class="header">Statistiche</li>
                  
                  @if (!in_array(RedisUser::get('role'), ['client', 'marketing', 'tecnical_sales']))
                  <li class="{{ Ekko::isActiveRoute('stFatt::idxAg') }}">
                    <a href="{{ route('stFatt::idxAg') }}">
                      <i class='fa fa-line-chart'></i>
                      <span>{{ trans('_menu.statsFatt')}} - {{trans('_menu.agent') }}</span>
                    </a>
                  </li>
                  @endif

                  @if (!in_array(RedisUser::get('role'), ['client', 'marketing']))
                  <li class="{{ Ekko::isActiveRoute('stAbc::idxArt') }}">
                    <a href="{{ route('stAbc::idxArt') }}">
                      <i class='fa fa-sort-alpha-asc'></i>
                      <span>{{ trans('_menu.AbcArt') }}</span>
                    </a>
                  </li>
                  @endif
                  
                  @if (!in_array(RedisUser::get('role'), ['client', 'marketing', 'tecnical_sales']))
                    <li class="{{ Ekko::isActiveRoute('stFattArt::idxAg') }}">
                        <a href="{{ route('stFattArt::idxAg') }}">
                            <i class='fa fa-sort-amount-desc'></i>
                            <span>{{ trans('_menu.statsFattArt') }}</span>
                        </a>
                    </li>
                  @endif

                  @if (!in_array(RedisUser::get('role'), ['marketing', 'tecnical_sales']))
                    <li class="{{ Ekko::isActiveRoute('Portfolio::idxAg') }}">
                        <a href="{{ route('Portfolio::idxAg') }}">
                            <i class='fa fa-stack-overflow'></i>
                            <span>Portafoglio</span>
                        </a>
                  @endif

                @endif
              @endif
            @endif
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
