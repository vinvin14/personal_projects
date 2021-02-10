@extends('movement.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
{{--    {{dd($data)}}--}}
    <a href="{{route('movement.components')}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Component Movements</a>
    <div class="card w-50 shadow-sm mb-4">
        <div class="card-body ">
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
            <h5 class="border-bottom">Movement Information</h5>
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
            <form action="{{route('movement.component.upsave', $data['data']->id)}}" method="post">
                @csrf
                <div class="row py-2">

                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Movement Type
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <select name="type" id="movementType" class="form-control" required>
                                <option value="">-</option>
                                <option value="incoming" @if($data['data']->type == 'incoming') selected @endif >Incoming</option>
                                <option value="outgoing" @if($data['data']->type == 'outgoing') selected @endif >Outgoing</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row py-2" id="incoming-invoicenumber">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Invoice Number
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="invoice_number" class="form-control"
                                   value="{{$data['data']->invoice_number}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2" id="outgoing-purpose">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Purpose
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <select name="purpose" id="purpose" class="form-control">
                                <option value="">-</option>
                                @foreach($data['purposes'] as $purpose)
                                    <option value="{{$purpose->id}}"
                                            @if($data['data']->purpose == $purpose->id)
                                            selected
                                        @endif
                                    >{{$purpose->purpose}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row py-2" id="outgoing-rma">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            RMA
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <select name="rma" id="" class="form-control">
                                <option value="">-</option>
                                @foreach($data['boards'] as $board)
                                    <option value="{{$board->id}}"
                                            @if($data['data']->rma == $board->id)
                                            selected
                                        @endif
                                    >{{$board->rma}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row py-2" id="outgoing-referencedesignator">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Reference Designator
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="reference_designator" class="form-control"
                                   value="{{$data['data']->reference_designator}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2" id="component">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Components
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <select name="reference" id="" class="form-control" required>
                                <option value="">-</option>
                                @foreach ($data['components'] as $component)
                                    <option value="{{$component->id}}"
                                        @if($data['data']->reference == $component->id)
                                            selected
                                        @endif
                                    >{{$component->description}} -
                                        <strong>{{$component->partNumber}}</strong>
                                        (<strong class="text-info">{{$component->actualQuantity}}</strong>)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row py-2" id="incoming-vendor">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Vendor
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="vendor" class="form-control"
                                   value="{{$data['data']->vendor}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2" id="incoming-brand">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Brand
                        </div>
                    </div>
                    <div class="col-8 text-left" id="incoming-brand">
                        <div class="text-info">
                            <input type="text" name="brand" class="form-control"
                                   value="{{$data['data']->brand}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Quantity
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="number" name="quantity" id="quantity" class="form-control"
                                   value="{{$data['data']->quantity}}" required>
                        </div>
                    </div>
                </div>
                <div class="row py-2" id="incoming-unitprice">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Unit Price
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="number" step=".01" name="unitPrice" id="unitPrice" class="form-control"
                                   value="{{$data['data']->unitPrice}}" @if($data['data']->type == 'outgoing') readonly @endif required>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Date Received
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="date" name="date_received_released" class="form-control"
                                   value="{{$data['data']->date_received_released}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Received/Dispensed By
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="received_released_by"  class="form-control"
                                   value="{{$data['data']->received_released_by}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Remarks
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <textarea name="remarks" id="" cols="30" rows="10"
                                      class="form-control">{{$data['data']->remarks}}</textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success float-right mt-1">Save Information</button>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var type = $('#movementType').val();
            if(type === 'incoming')
            {
                $('#incoming-invoicenumber').show();
                $('#incoming-vendor').show();
                $('#incoming-brand').show();

                $('#outgoing-purpose').hide();
                $('#outgoing-rma').hide();
                $('#outgoing-referencedesignator').hide();
            }
            else
            {
                $('#incoming-invoicenumber').hide();
                $('#incoming-vendor').hide();
                $('#incoming-brand').hide();

                $('#outgoing-purpose').show();
                $('#outgoing-rma').show();
                $('#outgoing-referencedesignator').show();
            }
            $('#movementType').click(function () {
                if($('#movementType').val() === 'incoming')
                {
                    $('#incoming-invoicenumber').show();
                    $('#incoming-vendor').show();
                    $('#incoming-brand').show();

                    $('#outgoing-purpose').hide();
                    $('#outgoing-rma').hide();
                    $('#outgoing-referencedesignator').hide();
                }
                else
                {
                    $('#incoming-invoicenumber').hide();
                    $('#incoming-vendor').hide();
                    $('#incoming-brand').hide();

                    $('#outgoing-purpose').show();
                    // $('#outgoing-rma').show();
                    $('#outgoing-referencedesignator').show();

                    if($('#purpose option:selected').text() === 'RMA')
                        $('#rma').show();
                }
            });
            $('#purpose').on('input', function () {
                var selectedOption = $('#purpose option:selected').text();
                if(selectedOption === 'RMA')
                {
                    $('#rma').show()
                }
                else
                {
                    $('#rma').hide()
                }
            });
            $('#unitPrice, #quantity').on('blur', function () {
                var unitPrice = parseFloat($('#unitPrice').val());
                var quantity = parseInt($('#quantity').val());
                $('#totalPrice').val(unitPrice * quantity);
            })
        });
    </script>
@endsection
