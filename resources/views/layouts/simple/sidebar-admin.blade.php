<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid for-light"
                    src="{{ asset('assets/images/logo/logo1.png') }}" style="width: 50px" alt=""><img class="img-fluid for-dark"
                    src="{{ asset('assets/images/logo/logo1.png') }}" style="width: 50px" alt=""></a><b style="color: #ffc526">IdzmirKidsHub</b>
            <div class="back-btn"><i class="fa fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
        </div>
        <div class="logo-icon-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid"
            src="{{ asset('assets/images/logo/logo1.png') }}" style="width: 50px" alt=""></a></div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid"
                                src="{{ asset('assets/images/logo/logo1.png') }}" alt=""></a>
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>

                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                        <a class="sidebar-link sidebar-title"
                            href="{{ route('admin.dashboard')}}">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use>
                            </svg><span class="lan-3">Dashboard</span></a>
                    </li>
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                        <label class="badge badge-light-primary">4</label><a class="sidebar-link sidebar-title"
                            href="#">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use>
                            </svg><span>All Users</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.parents') }}">Lists Parent</a></li>
                            <li><a href="{{ route('admin.cs.list') }}">Lists Operation Manager</a></li>
                            <li><a href="{{ route('admin.therapist.list') }}">Lists Therapist</a></li>
                            <li><a href="{{ route('admin.sales.list') }}">Lists Customer Service</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav" href="{{ route('admin.package.index')}}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-to-do') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-to-do') }}"> </use>
                        </svg><span>Product & Services</span></a></li>
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav" href="{{ route('admin.schedules.index')}}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-calendar') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-calendar') }}"> </use>
                        </svg><span>Schedules</span></a></li>
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                        <label class="badge badge-light-primary">2</label><a class="sidebar-link sidebar-title"
                            href="#">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#notification') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#notification') }}"></use>
                            </svg><span>Announcement</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.email.compose') }}">Create Announcement</a></li>
                            <li><a href="{{ route('admin.email.sent') }}">View Announcement</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                            class="sidebar-link sidebar-title link-nav" href="{{route('admin.payment.list')}}">
                            <i class="fa fa-money">   &nbsp;</i>
                            </svg><span>Billing and Payment</span></a></li>

                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                          <label class="badge badge-light-primary">2</label><a class="sidebar-link sidebar-title"
                              href="#">
                              <svg class="stroke-icon">
                                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-charts') }}"></use>
                              </svg>
                              <svg class="fill-icon">
                                  <use href="{{ asset('assets/svg/icon-sprite.svg#fill-charts') }}"></use>
                              </svg><span>Data Analytics</span></a>
                          <ul class="sidebar-submenu">
                              <li><a href="{{ route('admin.child.fa') }}">Full Assesment</a></li>
                              <li><a href="{{ route('admin.child.intervention') }}">Intervention</a></li>
                              <li><a href="{{ route('admin.child.rts') }}">RTS</a></li>
                          </ul>
                      </li>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
