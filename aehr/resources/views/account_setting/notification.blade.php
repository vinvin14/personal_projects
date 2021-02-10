@extends('account_setting.main')
@section('content3')
    <div class="lead border-bottom">Notification Details</div>
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
        <div class="form-group">
            <form action="{{route('account.notification.save', $data['email_recipient']->id)}}" method="post">
                @csrf
                <label for="" class="font-weight-bold">Email to Notify</label>
                <input type="text" name="email" class="form-control" value="{{$data['email_recipient']->email ?? ''}}" required>
                <button class="btn btn-outline-success mt-2">Save Email</button>
            </form>

        </div>
    </div>

@endsection
