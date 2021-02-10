@extends('movement.main')
@section('scripts')
    {{--<script src="{{asset('js/api.js')}}"></script>--}}
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('movement.consigned')}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Consigned Spare Movements</a>
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
                    <h5 class="border-bottom">Create New Movement For Consigned Spare</h5>
                    <form action="{{route('movement.consigned.store')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Movement Type</label>
                            <select name="type" id="movementType" class="form-control" required>
                                <option value="">-</option>
                                <option value="incoming">Incoming</option>
                                <option value="outgoing">Outgoing</option>
                            </select>
                        </div>
                        <div class="form-group" id="incoming-invoicenumber">
                            <label class="font-weight-bold" for="">Invoice Number</label>
                            <input type="text" class="form-control" name="invoice_number">
                            <small class="text-muted">Leave this blank if <strong>Movement Type is Outgoing</strong>
                            </small>
                        </div>
                        <div class="form-group" id="outgoing-purpose">
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
                        <div class="form-group" id="outgoing-consigned">
                            <label class="font-weight-bold" for="">Consigned Spares</label>
                            <select name="reference" id="" class="form-control">
                                <option value="">-</option>
                                @foreach($data['data'] as $consigned)
                                    <option value="{{$consigned['id']}}"> {{$consigned['description']}} |
                                         {{$consigned['partNumber']}} | {{$consigned['serialNumber']}}
                                        (<strong class="text-info">{{$consigned['actualQuantity']}}</strong>)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="incoming-description">
                            <label class="font-weight-bold" for="">Description</label>
                            <input type="text" class="form-control" name="description">
                        </div>
                        <div class="form-group" id="incoming-partnumber">
                            <label class="font-weight-bold" for="">Part Number</label>
                            <input type="text" class="form-control" name="partNumber">
                        </div>
                        <div class="form-group" id="incoming-serialnumber">
                            <label class="font-weight-bold" for="">Serial Number</label>
                            <input type="text" class="form-control" name="serialNumber">
                        </div>
                        <div class="form-group" id="incoming-systemtype">
                            <label class="font-weight-bold" for="">System Type</label>
                            <select name="systemType" id="" class="form-control">
                                <option value="">-</option>
                                @foreach($data['systemTypes'] as $systemType)
                                    <option value="{{$systemType['id']}}"> {{$systemType['systemType']}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<label class="font-weight-bold" for="">Board Type</label>--}}
                            {{--<select name="boardType" id="" class="form-control" required>--}}
                                {{--<option value="">-</option>--}}
                                {{--@foreach($data['boardTypes'] as $boardType)--}}
                                    {{--<option value="{{$boardType['id']}}"> {{$boardType['boardType']}}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        <div class="form-group" id="incoming-vendor">
                            <label class="font-weight-bold" for="">Vendor</label>
                            <input type="text" class="form-control" name="vendor">
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
                        {{--<div class="form-group" id="incoming-quantity">--}}
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
                        <div class="form-group" id="incoming-depreciationvalue">
                            <label class="font-weight-bold" for="">Depreciation Value</label>
                            <input type="number" step="0.01" class="form-control" name="depreciationValue">
                        </div>
                        <div class="form-group" id="incoming-usefullife">
                            <label class="font-weight-bold" for="">Useful Life</label>
                            <input type="number" class="form-control" name="usefulLife">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Date Received/Released</label>
                            <input type="date" class="form-control" name="date_received_released">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">FSE</label>
                            <input type="text" class="form-control" name="received_released_by" required>
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
                    <h5 class="border-bottom">Consigned Spare(s)</h5>
                    @foreach($data['data'] as $consigned)
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action mb-1">
                                <div class="row">
                                    <div class="col-8">
                                        {{$consigned['description']}} | <strong>{{$consigned['partNumber']}}</strong>
                                        (<strong class="text-info
                                    @if($consigned['actualQuantity'] <= $consigned['minimumQuantity'])
                                            text-danger
                                    @else
                                            text-info
                                    @endif
                                            ">
                                            {{$consigned['actualQuantity']}}
                                        </strong>)
                                        <div class="text-info"><small>Current Unit Price:</small> <strong>${{$consigned['unitPrice']}}</strong></div>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{route('consigned.show', $consigned['id'])}}" class="float-right">View</a>
                                    </div>
                                </div>
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
                $('#outgoing-consigned').hide();
                $('#outgoing-purpose').hide();
                $('#incoming-description').show();
                $('#incoming-partnumber').show();
                $('#incoming-systemtype').show();
                $('#incoming-serialnumber').show();
                $('#incoming-modelnumber').show();
                $('#incoming-description').show();
                $('#incoming-storedin').show();
                $('#incoming-location').show();
                $('#incoming-unit').show();
                $('#incoming-unitprice').show();
                $('#incoming-brand').show();
                $('#incoming-vendor').show();
                $('#incoming-invoicenumber').show();
                $('#incoming-depreciationvalue').show();
                $('#incoming-usefullife').show();
            }
            else
            {
                $('#outgoing-consigned').show();
                $('#outgoing-purpose').show();
                $('#incoming-description').hide();
                $('#incoming-partnumber').hide();
                $('#incoming-systemtype').hide();
                $('#incoming-serialnumber').hide();
                $('#incoming-modelnumber').hide();
                $('#incoming-description').hide();
                $('#incoming-storedin').hide();
                $('#incoming-location').hide();
                $('#incoming-unit').hide();
                $('#incoming-unitprice').hide();
                $('#incoming-brand').hide();
                $('#incoming-vendor').hide();
                $('#incoming-invoicenumber').hide();
                $('#incoming-depreciationvalue').hide();
                $('#incoming-usefullife').hide();
            }
            $('#movementType').on('input', function (){
                if($(this).val() === 'incoming')
                {
                    $('#outgoing-consigned').hide();
                    $('#outgoing-purpose').hide();
                    $('#incoming-description').show();
                    $('#incoming-partnumber').show();
                    $('#incoming-systemtype').show();
                    $('#incoming-serialnumber').show();
                    $('#incoming-modelnumber').show();
                    $('#incoming-description').show();
                    $('#incoming-storedin').show();
                    $('#incoming-location').show();
                    $('#incoming-unit').show();
                    $('#incoming-unitprice').show();
                    $('#incoming-brand').show();
                    $('#incoming-vendor').show();
                    $('#incoming-invoicenumber').show();
                    $('#incoming-depreciationvalue').show();
                    $('#incoming-usefullife').show();
                }
                else
                {
                    $('#outgoing-consigned').show();
                    $('#outgoing-purpose').show();
                    $('#incoming-description').hide();
                    $('#incoming-partnumber').hide();
                    $('#incoming-systemtype').hide();
                    $('#incoming-serialnumber').hide();
                    $('#incoming-modelnumber').hide();
                    $('#incoming-description').hide();
                    $('#incoming-storedin').hide();
                    $('#incoming-location').hide();
                    $('#incoming-unit').hide();
                    $('#incoming-unitprice').hide();
                    $('#incoming-brand').hide();
                    $('#incoming-vendor').hide();
                    $('#incoming-invoicenumber').hide();
                    $('#incoming-depreciationvalue').hide();
                    $('#incoming-usefullife').hide();
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
            //create a prompt for accomodation

        });
    </script>
@endsection
