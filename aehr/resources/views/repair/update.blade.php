@extends('layouts.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content2')
    <a href="{{route('repair.show', $data['data']->id)}}" class="btn btn-outline mt-1"><i class="fas fa-arrow-circle-left"></i> Go Back</a>
    <div class="row mb-3">
        <div class="col-6 border-right">
            <div class="card card-body shadow-sm">
                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Woaah an Error!</strong>  {{ Session::get('error')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if(Session::has('response'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Well done!</strong>  {{ Session::get('response')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <h5 class="border-bottom">Update Repair Information</h5>
                <form action="{{route('repair.upsave', $data['data']->id)}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Description</label>
                        <input type="text" name="description" style="text-transform:capitalize" value="{{$data['data']->description}}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Customer</label>
                        <select name="customer" id="" class="form-control" required>
                            <option value="">-</option>
                            @foreach($data['customers'] as $customer)
                                <option value="{{$customer['id']}}"
                                @if($data['data']->customer == $customer['id'])
                                    selected
                                @endif
                                >{{$customer['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Batch</label>--}}
                        {{--<input type="text" name="batch" class="form-control" value="{{$data['data']->batch}}">--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Transaction Date</label>--}}
                        {{--<input type="date" name="transactionDate" class="form-control" value="{{$data['data']->transactionDate}}" required>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Received By</label>--}}
                        {{--<input type="text" name="receivedBy" class="form-control" value="{{$data['data']->receivedBy}}" required>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Ship to Customer Name</label>--}}
                        {{--<input type="text" name="shipToCustomerName" class="form-control" value="{{$data['data']->shipToCustomerName}}" >--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Ship Date</label>--}}
                        {{--<input type="date" name="shipDate" class="form-control" value="{{$data['data']->shipDate}}" >--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Ship Address</label>--}}
                        {{--<input type="text" name="address" class="form-control" value="{{$data['data']->address}}" >--}}
                        {{--<small class="text-muted">(e.g. Street #/Bldg.# Street Name, Municipality, Province)</small>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Incoming Tracking</label>--}}
                        {{--<input type="text" name="incomingTracking" class="form-control" value="{{$data['data']->incomingTracking}}">--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Outgoing Tracking</label>--}}
                        {{--<input type="text" name="outgoingTracking" class="form-control" value="{{$data['data']->outgoingTracking}}">--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Contact Person</label>--}}
                        {{--<input type="text" name="contactPerson" class="form-control" value="{{$data['data']->contactPerson}}" >--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Contact Number</label>--}}
                        {{--<input type="text" name="contactNumber" class="form-control" value="{{$data['data']->contactNumber}}" >--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Status</label>
                        <select name="status" id="" class="form-control" required>
                            <option value="">-</option>
                            <option  @if($data['data']->status == 'Active') selected @endif>Active</option>
                            <option  @if($data['data']->status == 'Completed') selected @endif>Completed</option>
                            <option  @if($data['data']->status == 'Pending') selected @endif>Pending</option>
                            <option  @if($data['data']->status == 'Inactive') selected @endif>Inactive</option>
                            <option  @if($data['data']->status == 'Declined') selected @endif>Declined</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Remarks</label>
                        <textarea name="remarks" id="" cols="30" rows="10" class="form-control">{{$data['data']->remarks}}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success form-control">Update Repair Information</button>
                </form>
            </div>
        </div>
    </div>
@endsection
