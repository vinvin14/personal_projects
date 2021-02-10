@extends('layouts.main')
@section('title', 'Account Settings')
@section('content2')
    <div class="container-fluid">
        <h2 class="mb-4 border-bottom">Account Settings</h2>
        <div class="row">
            <div class="col-3 border-right">
                <div class="list-group">
                    <a href="{{route('account.settings')}}" class="list-group-item list-group-item-action @if($account_settings_selected == 'account_details') active @endif" aria-current="true">
                        Account Details
                    </a>
                    <a href="{{route('account.password')}}" class="list-group-item list-group-item-action @if($account_settings_selected == 'password') active @endif">Change Password</a>
                    <a href="{{route('account.notification')}}" class="list-group-item list-group-item-action @if($account_settings_selected == 'notification') active @endif">Notification Details</a>
                    {{--<a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>--}}
                    {{--<a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">Vestibulum at eros</a>--}}
                </div>
            </div>
            <div class="col-9">
                <div class="card">
                    <div class="card-body">
                        @yield('content3')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
