@extends('references.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    {{--{{dd($units)}}--}}
    <a href="{{route('reference.purposes')}}" class="btn btn-outline my-1"><i class="fas fa-arrow-circle-left"></i> Back to Purpose List</a>
    <div class="row">
        <div class="col-6">
            <form action="{{route('reference.purpose.store')}}" method="post">
                @csrf
                <div class="card">
                    <div class="card-body shadow-sm">
                        <h5 class="border-bottom">Create New Purpose</h5>
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
                            <label class="font-weight-bold" for="">Purpose</label>
                            <input type="text" name="purpose" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Description</label>
                            <textarea type="text" name="description" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success form-control">Add New Purpose</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body shadow-sm">
                    <h5 class="border-bottom">Existing Purpose(s)</h5>
                    @foreach($existing_purpose as $purpose)
                        <div class="list-group mb-1">
                            <a href="{{route('reference.purpose.update', $purpose->id)}}" class="list-group-item list-group-item-action">
                                {{$purpose->purpose}}
                            </a>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
