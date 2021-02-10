@extends('references.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    {{--{{dd($units)}}--}}
    <a href="{{route('reference.fses')}}" class="btn btn-outline my-1"><i class="fas fa-arrow-circle-left"></i> Back to FSE List</a>
    <div class="row">
        <div class="col-6">
            <form action="{{route('reference.fse.store')}}" method="post">
                @csrf
                <div class="card">
                    <div class="card-body shadow-sm">
                        <h5 class="border-bottom">Create New FSE Record</h5>
                        @if(Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                <strong>Woaah an Error!</strong>  {{ Session::get('error')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if(Session::has('response'))
                            <div class="alert alert-success alert-dismissible fade show mt-1" role="alert">
                                <strong>Well done!</strong>  {{ Session::get('response')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Last Name</label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">First Name</label>
                            <input type="text" name="firstname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Middle Name</label>
                            <input type="text" name="middlename" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Position</label>
                            <input type="text" name="position" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success form-control">Save FSE Record</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body shadow-sm">
                    <h5 class="border-bottom">Existing Field Engineer(s)</h5>
                    @foreach($existing_fse as $engineer)
                        <div class="list-group mb-1">
                            <a href="{{route('reference.fse.update', $engineer->id)}}" class="list-group-item list-group-item-action">
                                <strong>{{$engineer->lastname}}</strong>, <strong>{{$engineer->firstname}}</strong> <strong>{{$engineer->middlename}}</strong>
                            </a>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
