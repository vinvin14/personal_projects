@extends('layouts.main')
@section('title', 'Account Management')
@section('content2')
    <h2>Account Management</h2>
    <a href="{{route('account.management')}}" class="btn btn-outline my-1"><i class="fas fa-arrow-circle-left"></i> Go Back</a>
    <div class="pb-2 w-50">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-light">Account Information</div>
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
                <div class="form-group">
                    <label for="" class="font-weight-bold text-info">Full Name</label>
                    <div class="font-weight-bold">{{$selected_user->responsible_person}}</div>
                </div>
                <div class="form-group my-2">
                    <label for="" class="font-weight-bold text-info">Username</label>
                    <div class="font-weight-bold">{{$selected_user->username}}</div>
                </div>
                <form action="{{route('account.management.approve', $selected_user)}}" method="post">
                    @csrf
                <div class="form-group my-2">
                    <label for="" class="font-weight-bold text-info">Role</label>
                    <select name="role" id="" class="form-control" required>
                        <option value="">-</option>
                        <option value="encoder" @if($selected_user->role == 'encoder') selected @endif>Encoder</option>
                        <option value="admin" @if($selected_user->role == 'admin') selected @endif>Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success my-2 @if($selected_user->account_permission > 0) d-none @endif"><i class="fas fa-check"></i> Approve Account</button>
                </form>
                <a href="{{route('account.management.password.reset', $selected_user->id)}}" onclick="return confirm('Are you sure you want to reset account\'s password?')" class="btn btn-danger"><i class="fas fa-undo"></i> Reset Password</a>
            </div>
        </div>
    </div>
@endsection
