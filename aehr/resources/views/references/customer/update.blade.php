@extends('references.main')
@section('scripts')
<script src="{{asset('js/api.js')}}"></script>
<script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
{{--{{dd($units)}}--}}
<a href="{{route('reference.customers')}}" class="btn btn-outline my-1"><i class="fas fa-arrow-circle-left"></i> Back to Customer List</a>
<div class="row">
    <div class="col-6">
        <form action="{{route('reference.customer.upsave', $customer->id)}}" method="post">
            @csrf
            <div class="card card-body shadow-sm">
                <h5 class="border-bottom">Update Current Customer Record</h5>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Customer ID</label>
                    <input type="text" name="customerID" class="form-control" value="{{$customer->customerID}}" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Customer Name</label>
                    <input type="text" name="name" class="form-control" value="{{$customer->name}}" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Address</label>
                    <textarea type="text" name="address" class="form-control">{{$customer->address}}</textarea>
                    <small class="font-italic font-weight-bold">Address Format (Street #/Bldg. #, Street Name, Municipality, Province)</small>
                </div>
                <button type="submit" class="btn btn-success">Update Customer Record</button>
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
