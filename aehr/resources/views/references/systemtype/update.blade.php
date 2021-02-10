@extends('references.main')
@section('scripts')
<script src="{{asset('js/api.js')}}"></script>
<script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
{{--{{dd($units)}}--}}
<a href="{{route('reference.systemtypes')}}" class="btn btn-outline my-1"><i class="fas fa-arrow-circle-left"></i> Back to System Type</a>
<div class="row">
    <div class="col-6">
        <form action="{{route('reference.systemtype.upsave', $system_type->id)}}" method="post">
            @csrf
            <div class="card card-body shadow-sm">
                <h5 class="border-bottom">Update Current System Type</h5>
                <div class="form-group">
                    <label class="font-weight-bold" for="">System Type</label>
                    <input type="text" name="systemType" class="form-control" value="{{$system_type->systemType}}" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Description</label>
                    <textarea type="text" name="description" class="form-control">{{$system_type->description}}</textarea>
                </div>
                <button type="submit" class="btn btn-success">Update System Type Record</button>
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
