@extends('layouts.master')
@section('scripts')
    <script src="{{asset('js/login.js')}}"></script>
@endsection
@section('title', 'Account Registration')
@section('content')
<div class="container mt-5">
    <a href="{{route('login')}}"><i
            class="fas fa-arrow-circle-left"></i> Go Back to Login Page</a>
    <div class="card shadow ">
        <div class="card-header">Register An Account</div>
        <div class="card-body">

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
            <form action="{{route('register.save')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="">Full Name</label>
                    <input type="text" name="responsible_person" value="{{ old('responsible_person') }}" class="form-control" required>
                    <small class="font-weight-bold font-italic text-muted">Format: (Last Name, First Name MI. eg. Dela Cruz, Juan B.)</small>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="username">Password</label>
                    <input type="password" name="password" value="{{ old('password') }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="username">Confirm Password</label>
                    <input type="password" name="confirm_password" value="{{ old('confirm_password') }}" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success form-control my-2">Register</button>
            </form>
        </div>
    </div>
</div>
@endsection
