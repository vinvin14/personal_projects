@extends('layouts.master')
@section('scripts')
    <script src="{{asset('js/login.js')}}"></script>
    <style>
        * {
            {{--background-image:url({{url('images/homeback.jpg')}})--}}
        }
    </style>
@endsection
@section('title', 'AEHR')
@section('content')
<div class="container text-center mt-5" style="margin-top: 20vh !important;">
    <div class="jumbotron shadow">
        <img src="{{asset('images/not_auth.png')}}" alt="" style="height: 150px; width: 150px;"><h1><strong>Whoops Not Authorized!</strong> </h1>
        <p class="font-weight-bold lead text-muted">You are not authorized to access this page/perform this action.</p>
        <a href="{{ url()->previous() }}" class="lead"><i class="fas fa-home"></i> Let us go Home!</a>
    </div>
</div>
@endsection
