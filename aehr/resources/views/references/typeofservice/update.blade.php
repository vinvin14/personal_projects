@extends('references.main')
@section('scripts')
<script src="{{asset('js/api.js')}}"></script>
<script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
{{--{{dd($units)}}--}}
<a href="{{route('reference.typeofservices')}}" class="btn btn-outline my-1"><i class="fas fa-arrow-circle-left"></i> Back to Type of Service</a>
<div class="row">
    <div class="col-6">
        <form action="{{route('reference.typeofservice.upsave', $type_of_service->id)}}" method="post">
            @csrf
            <div class="card card-body shadow-sm">
                <h5 class="border-bottom">Update Current Type of Service</h5>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Type of Service</label>
                    <input type="text" name="typeOfService" class="form-control" value="{{$type_of_service->typeOfService}}" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Description</label>
                    <textarea type="text" name="description" class="form-control">{{$type_of_service->description}}</textarea>
                </div>
                <button type="submit" class="btn btn-success">Update Type of Service Record</button>
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
