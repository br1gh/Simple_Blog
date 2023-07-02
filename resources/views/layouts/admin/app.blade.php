<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        Simple Blog
    </title>
    <link rel="stylesheet" href="{{asset('admin/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('admin/vendors/jvectormap/jquery-jvectormap.css')}}">
    <link rel="stylesheet" href="{{asset('admin/vendors/owl-carousel-2/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/vendors/owl-carousel-2/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('admin/vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/vendors/select2-bootstrap-theme/select2-bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('admin/vendors/css/bootstrap-datepicker.css')}}">
    <link rel="shortcut icon" href="{{asset('admin/images/favicon.png')}}"/>
</head>
<body>
<div class="container-scroller">
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
            <a class="sidebar-brand brand-logo" href="index.html">
                Simple Blog
{{--                <img src="{{asset('admin/images/logo.svg')}}" alt="logo"/>--}}
            </a>
            <a class="sidebar-brand brand-logo-mini" href="index.html">
                SB
{{--                <img src="{{asset('admin/images/logo-mini.svg')}}" alt="logo"/>--}}
            </a>
        </div>
        <ul class="nav">
            <li class="nav-item profile">
                <div class="profile-desc">
                    <div class="profile-pic">
                        <div class="count-indicator">
{{--                            <img class="img-xs rounded-circle " src="{{asset('admin/images/faces/face15.jpg')}}" alt="">--}}
{{--                            <span class="count bg-success"></span>--}}
                        </div>
                        <div class="profile-name">
                            <h5 class="mb-0 font-weight-normal">
                                {{Auth::user()->username}}
                            </h5>
                            <span>
                                {{Auth::id() === 1 ? 'Super Admin' : 'Admin'}}
                            </span>
                        </div>
                    </div>
                </div>
            </li>
            <li class="nav-item nav-category">
                <span class="nav-link">
                    Navigation
                </span>
            </li>
            @php
                $currentName = Request::route()->getName();

            @endphp
            @foreach(config('navigation', []) as $nav)
                @if(isset($nav['items']))
                    <li class="nav-item menu-items">
                        <a class="nav-link{{in_array($currentName, Arr::pluck($nav['items'], 'route')) ? ' active' : ''}}"
                           data-bs-toggle="collapse" href="#{{$nav['label']}}" aria-expanded="false" aria-controls="{{$nav['label']}}"
                        >
                            <span class="menu-icon">
                               <i class="{{$nav['icon']}}"></i>
                            </span>
                            <span class="menu-title">
                                {{$nav['label']}}
                            </span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="{{$nav['label']}}">
                            <ul class="nav flex-column sub-menu">
                                @foreach(($nav['items']) as $item)
                                    <li class="nav-item">
                                        <a class="nav-link{{$item['route'] === $currentName ? ' text-primary' : ''}}"
                                           href="{{route($item['route'])}}">
                                            {{$item['label']}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="nav-item menu-items">
                        <a class="nav-link{{$nav['route'] === $currentName ? ' bg-primary' : ''}}"
                           href="{{route($nav['route'])}}">
                            <span class="menu-icon">
                                <i class="{{$nav['icon']}}"></i>
                            </span>
                            <span class="menu-title">
                                {{$nav['label']}}
                            </span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
    <div class="container-fluid page-body-wrapper">
        <nav class="navbar p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                <a class="navbar-brand brand-logo-mini" href="index.html">
{{--                    <img src="{{asset('admin/images/logo-mini.svg')}}" alt="logo"/>--}}
                </a>
            </div>
            <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
                <ul class="navbar-nav w-100">
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown d-none d-lg-block">
{{--                        <a class="nav-link btn btn-success create-new-button" id="createbuttonDropdown"--}}
{{--                           data-bs-toggle="dropdown" aria-expanded="false" href="#">--}}
{{--                            + Create New Project--}}
{{--                        </a>--}}
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                             aria-labelledby="createbuttonDropdown">
                            <h6 class="p-3 mb-0">
                                Projects
                            </h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-file-outline text-primary"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject ellipsis mb-1">
                                        Software Development
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-web text-info"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject ellipsis mb-1">
                                        UI Development
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-layers text-danger"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject ellipsis mb-1">
                                        Software Testing
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <p class="p-3 mb-0 text-center">
                                See all projects
                            </p>
                        </div>
                    </li>
                    <li class="nav-item dropdown border-left">
                        <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-email"></i>
                            <span class="count bg-success"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                             aria-labelledby="messageDropdown">
                            <h6 class="p-3 mb-0">
                                Messages
                            </h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="{{asset('admin/images/faces/face4.jpg')}}" alt="image"
                                         class="rounded-circle profile-pic">
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject ellipsis mb-1">
                                        Mark send you a message
                                    </p>
                                    <p class="text-muted mb-0">
                                        1 Minutes ago
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="{{asset('admin/images/faces/face2.jpg')}}" alt="image"
                                         class="rounded-circle profile-pic">
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject ellipsis mb-1">
                                        Cregh send you a message
                                    </p>
                                    <p class="text-muted mb-0">
                                        15 Minutes ago
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="{{asset('admin/images/faces/face3.jpg')}}" alt="image"
                                         class="rounded-circle profile-pic">
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject ellipsis mb-1">
                                        Profile picture updated
                                    </p>
                                    <p class="text-muted mb-0">
                                        18 Minutes ago
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <p class="p-3 mb-0 text-center">
                                4 new messages
                            </p>
                        </div>
                    </li>
                    <li class="nav-item dropdown border-left">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                           data-bs-toggle="dropdown">
                            <i class="mdi mdi-bell"></i>
                            <span class="count bg-danger"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                             aria-labelledby="notificationDropdown">
                            <h6 class="p-3 mb-0">
                                Notifications
                            </h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-calendar text-success"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">
                                        Event today
                                    </p>
                                    <p class="text-muted ellipsis mb-0">
                                        Just a reminder that you have an event today
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-settings text-danger"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">
                                        Settings
                                    </p>
                                    <p class="text-muted ellipsis mb-0">
                                        Update dashboard
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-link-variant text-warning"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">
                                        Launch Admin
                                    </p>
                                    <p class="text-muted ellipsis mb-0">
                                        New admin wow!
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <p class="p-3 mb-0 text-center">
                                See all notifications
                            </p>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                            <div class="navbar-profile">
{{--                                <img class="img-xs rounded-circle" src="{{asset('admin/images/faces/face15.jpg')}}"--}}
{{--                                     alt="">--}}
                                <p class="mb-0 d-none d-sm-block navbar-profile-name">
                                    {{Auth::user()->username}}
                                </p>
                                <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                             aria-labelledby="profileDropdown">
                            <a class="dropdown-item preview-item" href="{{ route('user', ['user' => Auth::user()]) }}">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-account text-info"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">
                                        Profile
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item" href="{{ route('admin.users.edit', ['id' => Auth::user()->id]) }}">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-settings text-success"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">
                                        Settings
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item"
                               href="{{ route('logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                            >
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-logout text-danger"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject mb-1">
                                        Log out
                                    </p>
                                </div>
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-toggle="offcanvas">
                    <span class="mdi mdi-format-line-spacing"></span>
                </button>
            </div>
        </nav>
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<script src="{{asset('admin/js/jquery.js')}}"></script>
<script src="{{asset('admin/vendors/js/vendor.bundle.base.js')}}"></script>
<script src="{{asset('admin/vendors/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('admin/vendors/progressbar.js/progressbar.min.js')}}"></script>
<script src="{{asset('admin/vendors/jvectormap/jquery-jvectormap.min.js')}}"></script>
<script src="{{asset('admin/vendors/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<script src="{{asset('admin/vendors/owl-carousel-2/owl.carousel.min.js')}}"></script>
<script src="{{asset('admin/js/jquery.cookie.js')}}" type="text/javascript"></script>
<script src="{{asset('admin/js/off-canvas.js')}}"></script>
<script src="{{asset('admin/js/hoverable-collapse.js')}}"></script>
<script src="{{asset('admin/js/misc.js')}}"></script>
<script src="{{asset('admin/js/settings.js')}}"></script>
<script src="{{asset('admin/js/todolist.js')}}"></script>
<script src="{{asset('admin/js/dashboard.js')}}"></script>
<script src="{{asset('admin/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('admin/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('admin/js/admin.js')}}"></script>

@stack('js')
@stack('js.end')
</body>
</html>
