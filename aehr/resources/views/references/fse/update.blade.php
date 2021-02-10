@extends('references.main')
@section('scripts')
<script src="{{asset('js/api.js')}}"></script>
<script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
{{--{{dd($units)}}--}}
<a href="{{route('reference.fses')}}" class="btn btn-outline my-1"><i class="fas fa-arrow-circle-left"></i> Back to Field Engineer List</a>
<div class="row">
    <div class="col-6">
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
        <form action="{{route('reference.fse.upsave', $fse->id)}}" method="post">
            @csrf
            <div class="card card-body shadow-sm">
                <h5 class="border-bottom">Update Current FSE Record</h5>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Last Name</label>
                    <input type="text" name="lastname" class="form-control" value="{{$fse->lastname}}" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">First Name</label>
                    <input type="text" name="firstname" class="form-control" value="{{$fse->firstname}}" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Middle Name</label>
                    <input type="text" name="middlename" class="form-control" value="{{$fse->middlename}}" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Position</label>
                    <input type="text" name="position" class="form-control" value="{{$fse->position}}" required>
                </div>
                <button type="submit" class="btn btn-success">Update FSE Record</button>
            </div>
        </form>
    </div>
    {{--<div class="col-6">--}}
        {{--<div class="card card-body shadow-sm">--}}
            {{--<h5 class="border-bottom">Existing Unit(s)</h5>--}}
            {{--@foreach($existing_units as $unit)--}}
            {{--<div class="list-group">--}}
                {{--<a href="{{route('reference.unit.update', $unit->id)}}" class="list-group-item list-group-item-action">--}}
                    {{--{{$unit->unit}}--}}
                {{--</a>--}}

            {{--</div>--}}
            {{--@endforeach--}}
        {{--</div>--}}
    {{--</div>--}}
</div>
@endsection
