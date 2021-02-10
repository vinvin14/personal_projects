@extends('layouts.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('title', 'Create Board Type')
@section('content2')
<a href="{{route('repairs')}}" class="btn btn-outline mt-1"><i class="fas fa-arrow-circle-left"></i> Back to Repairs</a>
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
            <h5 class="border-bottom">New Board Type</h5>
            <form action="{{route('repair.store')}}" method="post">
                @csrf
                <div class="form-group">
                    <label class="font-weight-bold" for="">Description</label>
                    <input type="text" name="description" style="text-transform:capitalize"  class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Customer</label>
                    <select name="customer" id="" class="form-control" required>
                        <option value="">-</option>
                        @foreach($data['customers'] as $customer)
                            <option value="{{$customer['id']}}">{{$customer['name']}}</option>
                        @endforeach
                    </select>
                </div>
                {{--<div class="form-group">--}}
                    {{--<label class="font-weight-bold" for="">Transaction Date</label>--}}
                    {{--<input type="date" name="transactionDate" class="form-control" required>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="font-weight-bold" for="">Received By</label>--}}
                    {{--<input type="text" name="receivedBy" class="form-control" required>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="font-weight-bold" for="">Ship to Customer Name</label>--}}
                    {{--<input type="text" name="shipToCustomerName" class="form-control">--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="font-weight-bold" for="">Incoming Tracking</label>--}}
                    {{--<input type="text" name="incomingTracking" class="form-control">--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="font-weight-bold" for="">Outgoing Tracking</label>--}}
                    {{--<input type="text" name="outgoingTracking" class="form-control">--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="font-weight-bold" for="">Contact Person</label>--}}
                    {{--<input type="text" name="contactPerson" class="form-control" required>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="font-weight-bold" for="">Contact Number</label>--}}
                    {{--<input type="text" name="remarks" class="form-control" required>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label class="font-weight-bold" for="">Remarks</label>
                    <textarea name="remarks" id="" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-success form-control">Record New Board Type</button>
            </form>
        </div>
    </div>
    <div class="col-6">
        <div class="card card-body shadow-sm">
            <div style="overflow-y: auto; max-height: 600px !important;">
                <h5 class="border-bottom pl-2">Existing Repair Record(s)</h5>
                <div class="p-2">
                    @foreach (@$data['repairs'] as $repair)
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action mb-1">
                                {{$repair->description}} - <strong>{{$repair->customer}}</strong>
                                (<strong class="text-info">
                                    {{$repair->totalJob}}
                                </strong>)
                                <a href="{{route('repair.show', $repair->id)}}" class="float-right">View</a>
                            </li>
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
