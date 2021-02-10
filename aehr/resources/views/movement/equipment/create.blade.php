@extends('movement.main')
@section('scripts')
    {{--<script src="{{asset('js/api.js')}}"></script>--}}
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('movement.equipment')}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Equipment Movements</a>
    <div class="row">
        <div class="col-6">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Woaah an Error!</strong> {{ Session::get('error')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(Session::has('response'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Well done!</strong> {{ Session::get('response')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <h5 class="border-bottom">Create New Movement For Equipment</h5>
                    <form action="{{route('movement.equipment.store')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Movement Type</label>
                            <select name="type" id="movementType" class="form-control" required>
                                <option value="">-</option>
                                <option value="incoming">Incoming</option>
                                <option value="outgoing">Outgoing</option>
                            </select>
                        </div>
                        <div class="form-group" id="invoiceNumber">
                            <label class="font-weight-bold" for="">Invoice Number</label>
                            <input type="text" class="form-control" name="invoice_number">
                            <small class="text-muted">Leave this blank if <strong>Movement Type is Outgoing</strong>
                            </small>
                        </div>
                        <div class="form-group" id="purpose">
                            <label class="font-weight-bold" for="">Purpose</label>
                            <select name="purpose" id="" class="form-control">
                                <option value="">-</option>
                                @foreach($data['purpose'] as $purpose)
                                    <option value="{{$purpose['id']}}">{{$purpose['purpose']}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{--<div class="form-group" id="rma">--}}
                            {{--<label class="font-weight-bold" for="">RMA</label>--}}
                            {{--<select name="rma" id="" class="form-control">--}}
                                {{--<option value="">-</option>--}}
                                {{--@foreach($data['boards'] as $board)--}}
                                    {{--<option value="{{$board['id']}}">{{$board['rma']}}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        <div class="form-group" id="outgoing-equipment">
                            <label class="font-weight-bold" for="">Equipment</label>
                            <select name="reference" id="" class="form-control">
                                <option value="">-</option>
                                @foreach($data['equipment'] as $equipment)
                                    <option value="{{$equipment['id']}}"> {{$equipment['description']}} |
                                        <strong>{{$equipment['partNumber']}} | {{$equipment['serialNumber']}}</strong>
                                        (<strong class="text-info">{{$equipment['actualQuantity']}}</strong>)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Equipment Orientation: <strong>Description | Part Number | Serial Number (Quantity)</strong></small>
                        </div>
                        <div class="form-group" id="incoming-partnumber">
                            <label class="font-weight-bold" for="">Part Number</label>
                            <input type="text" class="form-control" name="partNumber">
                        </div>
                        <div class="form-group" id="incoming-description">
                            <label class="font-weight-bold" for="">Description</label>
                            <input type="text" class="form-control" name="description">
                        </div>
                        <div class="form-group" id="incoming-serialnumber">
                            <label class="font-weight-bold" for="">Serial Number</label>
                            <input type="text" class="form-control" name="serialNumber">
                        </div>
                        <div class="form-group" id="incoming-modelnumber">
                            <label class="font-weight-bold" for="">Model Number</label>
                            <input type="text" class="form-control" name="modelNumber">
                        </div>
                        <div class="form-group" id="incoming-brand">
                            <label class="font-weight-bold" for="">Brand</label>
                            <input type="text" class="form-control" name="brand">
                        </div>
                        <div class="form-group" id="incoming-vendor">
                            <label class="font-weight-bold" for="">Vendor</label>
                            <input type="text" class="form-control" name="vendor">
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<label class="font-weight-bold" for="">Quantity</label>--}}
                            {{--<input type="number" class="form-control" name="quantity">--}}
                        {{--</div>--}}
                        <div class="form-group" id="incoming-unit">
                            <label class="font-weight-bold" for="">Unit</label>
                            <select name="unit" id="" class="form-control">
                                <option value="">-</option>
                                @foreach($data['units'] as $unit)
                                    <option value="{{$unit['id']}}">{{$unit['unit']}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group" id="incoming-unitprice">
                            <label class="font-weight-bold" for="">Unit Price</label>
                            <input type="number" class="form-control" step=".01" name="unitPrice">
                        </div>
                        <div class="form-group" id="incoming-storedin">
                            <label class="font-weight-bold" for="">Stored In</label>
                            <select name="storedIn" id="" class="form-control">
                                <option value="">-</option>
                                @foreach (@$data['storedIn'] as $location)
                                    <option value="{{$location['id']}}">{{@$location['location']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="incoming-location">
                            <label class="font-weight-bold" for="">Location</label>
                            <input type="text" name="location" id="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Date Received/Dispensed</label>
                            <input type="date" class="form-control" name="dateReceived">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">FSE</label>
                            <input type="text" class="form-control" name="receivedBy" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Remarks</label>
                            <textarea name="remarks" id="" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <button class="btn btn-success form-control">Record Item Movement</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="border-bottom">Equipment(s)</h5>
                    @foreach($data['equipment'] as $equipment)
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
                                </strong>) <span class="text-info">  <small>Current Unit Price:</small> <strong>${{$equipment['unitPrice']}}</strong></span>
                                <a href="{{route('equipment.show', $equipment['id'])}}" class="float-right">View</a>
                            </li>
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            if($('#movementType').val() === 'incoming')
            {
                $('#purpose').hide();
                $('#invoiceNumber').show();
                $('#vendor').show();
                $('#rma').hide();
                $('#outgoing-equipment').hide();
                $('#incoming-partnumber').show();
                $('#incoming-serialnumber').show();
                $('#incoming-modelnumber').show();
                $('#incoming-description').show();
                $('#incoming-storedin').show();
                $('#incoming-location').show();
                $('#incoming-unit').show();
                $('#incoming-unitprice').show();
                $('#incoming-brand').show();
                $('#incoming-vendor').show();

            }
            else
            {
                $('#invoiceNumber').hide();
                $('#purpose').show();
                $('#vendor').hide();
                $('#rma').hide();
                $('#outgoing-equipment').show();
                $('#incoming-partnumber').hide();
                $('#incoming-serialnumber').hide();
                $('#incoming-modelnumber').hide();
                $('#incoming-description').hide();
                $('#incoming-storedin').hide();
                $('#incoming-location').hide();
                $('#incoming-unit').hide();
                $('#incoming-unitprice').hide();
                $('#incoming-brand').hide();
                $('#incoming-vendor').hide();
            }

            $('#movementType').on('input', function (){
                if($(this).val() === 'incoming')
                {
                    $('#purpose').hide();
                    $('#invoiceNumber').show();
                    $('#vendor').show();
                    $('#rma').hide();
                    $('#outgoing-equipment').hide();
                    $('#incoming-partnumber').show();
                    $('#incoming-serialnumber').show();
                    $('#incoming-modelnumber').show();
                    $('#incoming-description').show();
                    $('#incoming-storedin').show();
                    $('#incoming-location').show();
                    $('#incoming-unit').show();
                    $('#incoming-unitprice').show();
                    $('#incoming-brand').show();
                    $('#incoming-vendor').show();
                }
                else
                {
                    $('#invoiceNumber').hide();
                    $('#purpose').show();
                    $('#vendor').hide();
                    $('#rma').hide();
                    $('#outgoing-equipment').show();
                    $('#incoming-partnumber').hide();
                    $('#incoming-serialnumber').hide();
                    $('#incoming-modelnumber').hide();
                    $('#incoming-description').hide();
                    $('#incoming-storedin').hide();
                    $('#incoming-location').hide();
                    $('#incoming-unit').hide();
                    $('#incoming-unitprice').hide();
                    $('#incoming-brand').hide();
                    $('#incoming-vendor').hide();

                }
            });
            // $('#purpose').on('input', function () {
            //     var selectedOption = $('#purpose option:selected').text();
            //     if(selectedOption === 'RMA')
            //     {
            //         $('#rma').show()
            //     }
            //     else
            //     {
            //         $('#rma').hide()
            //     }
            // });
            // //create a prompt for accomodation

        });
    </script>
@endsection
