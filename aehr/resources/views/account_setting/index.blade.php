@extends('account_setting.main')
@section('content3')
    <div class="lead border-bottom">Account Details</div>
    <div class="m-3">
        <div class="m-1">Username: <strong>{{ucfirst($_SESSION['user'])}}</strong></div>
        <div class="m-1">Role: <strong>{{ucfirst($_SESSION['role'])}}</strong></div>
    </div>
@endsection
