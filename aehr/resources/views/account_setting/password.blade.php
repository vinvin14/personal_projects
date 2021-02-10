@extends('account_setting.main')
@section('content3')
    <div class="lead border-bottom">Password Details</div>
    <div class="m-3">
        @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                <strong>Woaah an Error!</strong> {{ Session::get('error')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(Session::has('response'))
            <div class="alert alert-success alert-dismissible fade show mt-1" role="alert">
                <strong>Success!</strong> {{ Session::get('response')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <form action="{{route('account.change_password')}}" method="post">
        @csrf
        <input type="hidden" name="requestor" value="{{$_SESSION['user']}}">
        <div class="col-6 font-weight-bold">
            <label for="" class="font-weight-bold">Current Password</label>
            <input type="password" class="form-control" name="current_password" value="{{Session::get('current_password') ?? ''}}" required>
            <small class="font-italic">Please enter your current password to proceed with the updating</small>
        </div>
        <div class="mt-5">
            <div class="col-6 font-weight-bold">
                <h5 class="border-bottom text-info">Update Password</h5>
                <label for="" class="font-weight-bold">New Password</label>
                <input type="text" class="form-control" name="new_password" value="{{Session::get('new_password') ?? ''}}" required>
                <small class="font-italic">Must have 8 characters | Must have numeric characters</small>
            </div>
            <div class="col-6 font-weight-bold mt-3">
                <label for="" class="font-weight-bold">Confirm New Password</label>
                <input type="text" class="form-control" name="confirm_new_password" value="{{Session::get('confirm_new_password') ?? ''}}" required>
                <div><small class="font-italic">Must have 8 characters | Must have numeric characters</small></div>
                <button class="btn btn-primary mt-2">Update Password</button>
            </div>

            </form>
        </div>
    </div>

@endsection
