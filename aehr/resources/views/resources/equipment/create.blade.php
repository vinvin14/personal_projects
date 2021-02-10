@extends('resources.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('equipment')}}" class="btn btn-outline mt-1"><i class="fas fa-arrow-circle-left"></i> Back to Equipment</a>
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
                <h5 class="border-bottom">Create new Equipments</h5>
                <form action="{{route('equipment.store')}}" method="post">
                    @csrf
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Transaction</label>--}}
                        {{--<select name="transaction" id="transaction" class="form-control">--}}
                            {{--<option value="">-</option>--}}
                            {{--<option>Initial Balancing</option>--}}
                            {{--<option>Incoming</option>--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    {{--<div class="form-group" id="invoiceNumber" style="display: none">--}}
                        {{--<label class="font-weight-bold" for="">Invoice Number</label>--}}
                        {{--<input type="text" class="form-control" name="invoiceNumber">--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Description</label>
                        <input type="text" name="description" style="text-transform:capitalize"  class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Part Number</label>
                        <input type="text" name="partNumber" style="text-transform:uppercase" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Serial Number</label>
                        <input type="text" name="serialNumber" class="form-control">
                    </div>
                    <div class="form-group" id="modelNumber">
                        <label class="font-weight-bold" for="">Model Number</label>
                        <input type="text" name="modelNumber" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Brand</label>
                        <input type="text" name="brand" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Vendor</label>
                        <input type="text" name="vendor" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Stored In</label>
                        <select name="storedIn" id="" class="form-control" required>
                            <option value="">-</option>
                            @foreach (@$data['storedIn'] as $location)
                                <option value="{{$location['id']}}">{{@$location['location']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Location</label>
                        <input type="text" name="location" id="" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Quantity</label>
                        <input type="number" name="actualQuantity" id="actualQuantity" class="form-control" value="1" readonly>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Unit</label>
                        <select name="unit" id="" class="form-control" required>
                            <option value="">-</option>
                            @foreach (@$data['units'] as $unit)
                                <option value="{{$unit['id']}}">{{@$unit['unit']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Unit Price</label>
                        <input type="number" name="unitPrice" step=".01" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Useful Life in Year(s)</label>
                        <input type="number" name="usefulLife" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Depreciation Value</label>
                        <input type="number" name="depreciationValue" step=".01" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Date Received</label>
                        <input type="date" name="dateReceived" class="form-control" required>
                    </div>
                    <div class="form-group" id="receivedBy" style="display: none">
                        <label class="font-weight-bold" for="">Received By</label>
                        <input type="text" name="receivedBy" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="10"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success form-control">Save Equipment</button>
                </form>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-body shadow-sm">
                <div style="overflow-y: auto; max-height: 600px !important;">
                    <h5 class="border-bottom pl-2">Existing Equipment</h5>
                    <div class="p-2">
                        @foreach (@$data['equipment'] as $equipment)
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-action mb-1">
                                    {{$equipment['description']}} - <strong>{{$equipment['partNumber']}}</strong>
                                    (<strong class="text-info
                                    @if($equipment['actualQuantity'] <= $equipment['minimumQuantity'])
                                        text-danger
                                    @else
                                        text-info
                                    @endif
                                        ">
                                        {{$equipment['actualQuantity']}}
                                    </strong>)
                                    <a href="{{route('equipment.show', $equipment['id'])}}" class="float-right">View</a>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#transaction').on('input', function (){
                if($(this).val() === 'Incoming')
                {
                    $('#invoiceNumber').show();
                    $('#receivedBy').show();
                }
                else
                {
                    $('#invoiceNumber').hide();
                    $('#receivedBy').hide();
                }
            });
        })
    </script>
@endsection
