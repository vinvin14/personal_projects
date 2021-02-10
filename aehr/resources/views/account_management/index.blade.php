@extends('layouts.main')
@section('title', 'Account Management')
@section('content2')
    <h2>Account Management</h2>
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Woaah an Error!</strong> {{ Session::get('error')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(Session::has('response'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Well done!</strong> {{ Session::get('response')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row">
        <div class="col-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-light">List of Accounts to be Approved ({{count($pending_accounts)}})</div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($pending_accounts as $paccount)
                            <a href="{{route('account.management.show', $paccount->id)}}" class="list-group-item list-group-item-action">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="font-weight-bold">Username</div>
                                        {{$paccount->username}}
                                    </div>
                                    <div class="col-6"></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="p-2">
                        {{$pending_accounts->render()}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-light">List of Active Accounts ({{count($active_accounts)}})</div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($active_accounts as $account)
                            <a href="{{route('account.management.show', $account->id)}}" class="list-group-item list-group-item-action">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="font-weight-bold">Username</div>
                                        {{$account->username}}
                                    </div>
                                    <div class="col-6">
                                        <div class="font-weight-bold">Role</div>
                                        {{$account->role}}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="p-2">
                        {{$active_accounts->render()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    {{--<div class="mt-4 w-50">--}}
        {{--<div class="card">--}}
            {{--<div class="card-header bg-dark text-light">Request for Account Information Change</div>--}}
            {{--<div class="card-body">--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection
