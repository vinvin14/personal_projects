@extends('references.main')
@section('scripts')
<script src="{{asset('js/api.js')}}"></script>
<script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
{{--{{dd($units)}}--}}
<a href="{{route('reference.faultcodes')}}" class="btn btn-outline my-1"><i class="fas fa-arrow-circle-left"></i> Back to Fault Codes</a>
<div class="row">
    <div class="col-6">
        <form action="{{route('reference.faultcode.upsave', $faultcode->id)}}" method="post">
            @csrf
            <div class="card card-body shadow-sm">
                <h5 class="border-bottom">Update Current Fault Code</h5>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Code</label>
                    <input type="text" name="code" class="form-control" value="{{$faultcode->code}}" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Type</label>
                    <input type="text" name="type" class="form-control" value="{{$faultcode->type}}" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Description</label>
                    <textarea type="text" name="description" class="form-control">{{$faultcode->description}}</textarea>
                </div>
                <button type="submit" class="btn btn-success">Update Fault Code Record</button>
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
