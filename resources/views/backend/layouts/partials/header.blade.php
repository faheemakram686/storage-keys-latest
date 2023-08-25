<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="/" class="logo-link">
                    <img class="logo-light logo-img" src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" srcset="{{ asset('sk-assets/assets/images/logo2x.png') }} 2x" alt="logo">
                    <img class="logo-dark logo-img" src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" srcset="{{ asset('sk-assets/assets/images/dark2x.png') }} 2x" alt="logo-dark">
                </a>
            </div><!-- .nk-header-brand -->
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li> <a href="{{url('/')}}" target="_blank"  class="btn btn-outline-primary btn-dim btn-sm d-none d-md-inline-flex"><em class="icon ni ni-external-alt mr-1"></em> Visit Site</a></li>
                    <li class="dropdown notification-dropdown">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                            <div class=" {{(count(Auth::user()->unreadnotifications) >= 1)? "icon-status icon-status-info":""}} "><em class="icon ni ni-bell"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">Notifications</span>
                                <a href="{{route('notification.markAsRead')}}">Mark All as Read</a>
                            </div>
                            <div class="dropdown-body">
                                <div class="nk-notification">
                                    @if(count(Auth::user()->unreadnotifications) >= 1)
                                    @foreach(Auth::user()->unreadnotifications as $notification)
                                        <div class="nk-notification-item dropdown-inner">
                                            <div class="nk-notification-icon">
                                                <em class="icon icon-circle bg-warning-dim ni ni-bell"></em>
                                            </div>
                                            <div class="nk-notification-content">
                                                <a href="{{$notification->data['url']}}">
                                                <div class="nk-notification-text"> {{$notification->data['message']}} </div>
                                                </a>
                                                <div class="nk-notification-time">{{$notification->created_at->format('d M , Y H:00')}}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @else
                                        <div class="nk-notification-item dropdown-inner">
                                            <div class="nk-notification-content">
                                                <div class="nk-notification-text">You have not any <span> Notifications</span></div>
                                            </div>
                                        </div>
                                    @endif



                                </div><!-- .nk-notification -->
                            </div><!-- .nk-dropdown-body -->
                            <div class="dropdown-foot center">
                                <a href="{{route('notification.index')}}">View All</a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown user-dropdown" style="display: inherit!important">
                        <a class="dropdown-toggle mr-n1" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    @if(Auth::user()->avatar != 'default.png')
                                    <img src="{{asset('sk-assets/assets/images/avatar.png')}}" alt="store logo" class="" style="max-width:50px;max-height:50px"/>
                                @else    
                                     <img src="@isset (Auth::user()->avatar){{asset('storage/users/images/avatar/'.Auth::user()->avatar)}} @else {{asset('assets/images/cloud-uploading.png')}} @endif" alt="store logo" class="" style="max-width:50px;max-height:50px"/>
                                @endif
                                </div>
                                <div class="user-info d-none d-xl-block">
                                    {{-- <div class="user-status user-status-unverified">Unverified</div> --}}
                                    <div class="user-name dropdown-indicator">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-content2">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        @if(Auth::user()->avatar != 'default.png')   
                                            <img src="{{asset('sk-assets/assets/images/avatar.png')}}" alt="store logo" class="" style="max-width:50px;max-height:50px"/>
                                        @else    
                                            <img src="@isset (Auth::user()->avatar){{asset('storage/users/images/avatar/'.Auth::user()->avatar)}} @else {{asset('assets/images/cloud-uploading.png')}} @endif" alt="store logo" class="" style="max-width:50px;max-height:50px"/>
                                        @endif
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                        <span class="sub-text">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{route('profile.index')}}"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
                                    <li><a href="#"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <em class="icon ni ni-signout"></em>
                                            <span>Sign out</span>
                                        </a></li>
                                </ul>
                            </div>
                            <form id="logout-form" action="{{ url('admin/log-out') }}" method="get" class="d-none">@csrf</form>
                        </div>
                    </li>
                </ul>
            </div>
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>