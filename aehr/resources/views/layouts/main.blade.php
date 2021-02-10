@extends('layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{asset('/css/interface.css')}}">
    @endsection
@section('content')
    <nav class="navbar navbar-dark sticky-top bg-light flex-md-nowrap p-0 shadow-sm">
        <a class="navbar-brand bg-light col-md-3 col-lg-2 mr-0 px-3" href="#">
              <img src="/includes/images/aehr.png" alt="" style="width: 160px; height: 30px"> <span class="font-weight-bold text-dark">Philippines Inc.</span></a>

        <button class="navbar-toggler bg-dark position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="row mr-1">
            <div class="col-3 mr-3">
                <div class="dropdown show mt-2">
                    <span class="dropdown-toggle " href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell" style="font-size: 20px; color: #000;"></i>
                        <span id="notification-number" class="text-danger font-weight-bold"></span>
                    </span>

                    <div class="dropdown-menu dropdown-menu-right p-3 shadow" aria-labelledby="dropdownMenuLink2">
                        <h6 class="font-weight-bold border-bottom">Notifications</h6>
                        <div class="p-2" id="notification-info" style="min-width: 500px"></div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="dropdown btn-group">
                    <button type="button" class="btn btn-light shadow-sm dropdown-toggle nav-button-util" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-cog"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{route('account.settings')}}" type="button">Account Settings</a>
                        <a class="dropdown-item" type="button">Preferences</a>
                        <a class="dropdown-item" type="button" href="/logout">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        @foreach ($_SESSION['sidebar_links'] as $link)
                            <li class="nav-item">
                                <a class="nav-link sidebar-link py-2
                                    @if(@$sidebar_selected == $link['label'])
                                        active
                                    @endif
                                    " href="{{@$link['link']}}">
                                    <i class="{{@$link['icon']}} mr-2"></i>
                                    {{@$link['label']}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 mt-3 bg-light">
                @yield('content2')
            </main>
        </div>
    </div>
    <script>
        notification();

        setInterval(function () {
            notification()
        }, 10000);
        function notification()
        {
            $.ajax({
                async: true,
                url: '/api/notifications',
                type: 'get',
                success: function (data) {
                    if(data > 0)
                        $('#notification-number').text(data);
                    $.ajax({
                        url: '/api/notifications/info',
                        type: 'get',
                        success: function (data) {
                            // console.log(data);
                            $('#notification-info').html('')
                            data.forEach(function (val) {
                                $('#notification-info').append('' +
                                    '<div class="border-bottom">' +
                                    '<div class="row"> ' +
                                    '<div class="col-9">' +
                                    '<a href="'+ val.link +'" class="p-2 container-fluid"><p>'+val.details+' from <strong>'+val.origin+'</storng></p></a>' +
                                    '</div>' +
                                    '<div class="col-3"> ' +
                                    '<small class="float-right text-info font-weight-bold">'+val.created+'</small>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>')
                            })

                        }
                    });
                }
            });
        }
        function resolveNotifications()
        {
            $.ajax({
                async: true,
                url: '/api/notifications'
            });
        }
    </script>
@endsection
{{--<div class="list-group">--}}
    {{--<span href="#" class="list-group-item list-group-item-action disabled">Vestibulum at eros</span>--}}
{{--</div>--}}
