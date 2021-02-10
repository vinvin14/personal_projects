@extends('movement.main')
@section('scripts')
    {{--<script src="{{asset('js/api.js')}}"></script>--}}
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('movement.consumables')}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Consumable Movements</a>
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
                    <h5 class="border-bottom">Create New Movement For Consumables</h5>
                    <form action="{{route('movement.consumable.store')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Movement Type</label>
                            <select name="type" id="movementType" class="form-control" required>
                                <option value="">-</option>
                                <option value="incoming">Incoming</option>
                                <option value="outgoing">Outgoing</option>
                            </select>
                        </div>
                        <div class="form-group" id="incoming-referencetype">
                            <label class="font-weight-bold" for="">Reference Type</label>
                            <select name="reference_type" id="reference_type" class="form-control">
                                <option value="">-</option>
                                <option value="existing">Existing</option>
                                <option value="new">New</option>
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
                            <select name="purpose" id="purposeval" class="form-control">
                                <option value="">-</option>
                                @foreach($data['purpose'] as $purpose)
                                    <option value="{{$purpose['id']}}">{{$purpose['purpose']}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{--<div class="form-group" id="outgoing-rma">--}}
                            {{--<label class="font-weight-bold" for="">RMA</label>--}}
                            {{--<select name="rma" id="rmaval" class="form-control">--}}
                                {{--<option value="">-</option>--}}
                                {{--@foreach($data['boards'] as $board)--}}
                                    {{--<option value="{{$board['id']}}">{{$board['rma']}}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        <div class="form-group" id="consumable">
                            <label class="font-weight-bold" for="">Consumable</label>
                            <select name="reference" id="" class="form-control">
                                <option value="">-</option>
                                @foreach($data['data'] as $consumables)
                                    <option value="{{$consumables['id']}}"> {{$consumables['description']}} -
                                        <strong>{{$consumables['partNumber']}}</strong>
                                        (<strong class="text-info">{{$consumables['actualQuantity']}}</strong>)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="incoming-partnumber">
                            <label class="font-weight-bold" for="">Part Number</label>
                            <input type="text" class="form-control" name="partNumber">
                        </div>
                        <div class="form-group" id="incoming-description">
                            <label class="font-weight-bold" for="">Description</label>
                            <input type="text" class="form-control" name="description">
                        </div>
                        <div class="form-group" id="incoming-vendor">
                            <label class="font-weight-bold" for="">Vendor</label>
                            <input type="text" class="form-control" name="vendor">
                        </div>
                        <div class="form-group" id="incoming-brand">
                            <label class="font-weight-bold" for="">Brand</label>
                            <input type="text" class="form-control" name="brand">
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
                            <label class="font-weight-bold" for="">Quantity</label>
                            <input type="number" class="form-control" name="quantity">
                        </div>
                        <div class="form-group" id="new-minquantity">
                            <label class="font-weight-bold" for="">Minimum Quantity</label>
                            <input type="number" class="form-control" name="minimumQuantity">
                        </div>
                        <div class="form-group" id="new-maxquantity">
                            <label class="font-weight-bold" for="">Maximum Quantity</label>
                            <input type="number" class="form-control" name="maximumQuantity">
                        </div>
                        <div class="form-group" id="incoming-unit">
                            <label class="font-weight-bold" for="">Unit</label>
                            <select name="unit" id="" class="form-control">
                                <option value="">-</option>
                                @foreach (@$data['unit'] as $unit)
                                    <option value="{{$unit['id']}}">{{@$unit['unit']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="incoming-unitprice">
                            <label class="font-weight-bold" for="">Unit Price</label>
                            <input type="number" class="form-control" step=".01" name="unitPrice">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Date Received/Dispensed</label>
                            <input type="date" class="form-control" name="date_received_released" required>
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
                    <h5 class="border-bottom">Consumable(s)</h5>
                    @foreach($data['data'] as $consumables)
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action mb-1">
                                {{$consumables['description']}} - <strong>{{$consumables['partNumber']}}</strong>
                                (<strong class="text-info
                                    @if($consumables['actualQuantity'] <= $consumables['minimumQuantity'])
                                        text-danger
                                    @else
                                        text-info
                                    @endif
                                    ">
                                    {{$consumables['actualQuantity']}}
                                </strong>) <span class="text-info">  <small>Current Unit Price:</small> <strong>${{$consumables['unitPrice']}}</strong></span>
                                <a href="{{route('consumable.show', $consumables['id'])}}" class="float-right">View</a>
                            </li>
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var type = $('#movementType').val();
            var reference_type = $('#reference_type').val();
            if(type === 'incoming')
            {
                $('#incoming-referencetype').show();
                $('#incoming-invoicenumber').show();
                $('#incoming-partnumber').show();
                $('#incoming-description').show();
                $('#incoming-vendor').show();
                $('#incoming-brand').show();
                $('#incoming-storedin').show();
                $('#incoming-location').show();
                $('#incoming-unit').show();
                $('#incoming-unitprice').show();
                // $('#vendor').show();

                $('#outgoing-purpose').hide();
                $('#outgoing-rma').hide();

                if($('#reference_type').val() === 'new') {
                    $('#consumable').hide();
                }
                else{
                    $('#consumable').show();
                }
            }
            else
            {
                $('#incoming-referencetype').hide();
                $('#incoming-invoicenumber').hide();
                $('#incoming-partnumber').hide();
                $('#incoming-description').hide();
                $('#incoming-vendor').hide();
                $('#incoming-brand').hide();
                $('#incoming-storedin').hide();
                $('#incoming-location').hide();
                $('#incoming-unit').hide();
                $('#incoming-unitprice').hide();
                $('#vendor').hide();

                $('#outgoing-purpose').show();
                // $('#outgoing-rma').show();
            }
            $('#movementType').click(function () {
                if($('#movementType').val() === 'incoming')
                {
                    $('#incoming-referencetype').show();
                    $('#incoming-invoicenumber').show();
                    $('#incoming-partnumber').show();
                    $('#incoming-description').show();
                    $('#incoming-vendor').show();
                    $('#incoming-storedin').show();
                    $('#incoming-location').show();
                    $('#incoming-unit').show();
                    $('#incoming-unitprice').show();
                    $('#vendor').show();
                    $('#brand').show();

                    $('#outgoing-purpose').hide();

                }
                else
                {
                    $('#incoming-referencetype').hide();
                    $('#incoming-invoicenumber').hide();
                    $('#incoming-partnumber').hide();
                    $('#incoming-description').hide();
                    $('#incoming-vendor').hide();
                    $('#incoming-storedin').hide();
                    $('#incoming-location').hide();
                    $('#incoming-unit').hide();
                    $('#incoming-unitprice').hide();
                    $('#vendor').hide();
                    $('#new-minquantity').hide();
                    $('#new-maxquantity').hide();

                    $('#outgoing-purpose').show();
                    // $('#outgoing-rma').show();
                    $('#reference_type').val('');

                }
            });
            if(reference_type === 'new') {
                $('#consumable').hide();
                $('#incoming-partnumber').show();
                $('#incoming-description').show();
                $('#incoming-storedin').show();
                $('#incoming-location').show();
                $('#incoming-unit').show();
                $('#incoming-unitprice').show();
                $('#incoming-vendor').show();
                $('#incoming-brand').show();
                $('#new-minquantity').show();
                $('#new-maxquantity').show();
            } else {
                $('#consumable').show();
                $('#incoming-partnumber').hide();
                $('#incoming-description').hide();
                $('#incoming-storedin').hide();
                $('#incoming-location').hide();
                $('#incoming-unit').hide();
                $('#incoming-unitprice').hide();
                $('#incoming-vendor').show();
                $('#incoming-brand').show();
                $('#new-minquantity').hide();
                $('#new-maxquantity').hide();
            }
            $('#reference_type').click(function () {
                if($(this).val() === 'new') {
                    $('#consumable').hide();
                    $('#incoming-partnumber').show();
                    $('#incoming-description').show();
                    $('#incoming-storedin').show();
                    $('#incoming-location').show();
                    $('#incoming-unit').show();
                    $('#incoming-unitprice').show();
                    $('#incoming-vendor').show();
                    $('#incoming-brand').show();
                    $('#new-minquantity').show();
                    $('#new-maxquantity').show();
                }
                else {
                    $('#consumable').show();
                    $('#incoming-partnumber').hide();
                    $('#incoming-description').hide();
                    $('#incoming-storedin').hide();
                    $('#incoming-location').hide();
                    $('#incoming-unit').hide();
                    $('#incoming-unitprice').hide();
                    $('#incoming-vendor').show();
                    $('#incoming-brand').show();
                    $('#new-minquantity').hide();
                    $('#new-maxquantity').hide();
                }
            });
            $('#purposeval').on('blur', function () {
                var selectedOption = $('#purposeval option:selected').text();
                console.log(selectedOption);
                if(selectedOption === 'RMA')
                {
                    $('#outgoing-rma').show()
                }
                else
                {
                    $('#outgoing-rma').hide()
                }
            });

            //create a prompt for accomodation

        });
    </script>
@endsection
