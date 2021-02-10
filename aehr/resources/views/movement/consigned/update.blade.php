@extends('movement.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    {{--{{dd($data)}}--}}
    <a href="{{route('movement.consigned')}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Consigned Spares Movements</a>
    <div class="card w-50 shadow-sm mb-4">
        <div class="card-body ">
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
            <form action="{{route('movement.consigned.upsave', $data['data']->id)}}" method="post">
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
                                <option value="{{$data['data']->type}}">{{ucfirst($data['data']->type)}}</option>
                                {{--<option value="incoming" @if($data['data']->type == 'incoming') selected @endif>Incoming</option>--}}
                                {{--<option value="outgoing" @if($data['data']->type == 'outgoing') selected @endif>Outgoing</option>--}}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row py-2" id="invoiceNumber">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Invoice Number
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="invoice_number" class="form-control"
                                   value="{{$data['data']->invoice_number ?? ''}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2" id="purpose">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Purpose
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <select name="purpose" id="" class="form-control">
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
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Consigned Spare
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <select name="reference" id="" class="form-control">
                                <option value="{{$data['data']->reference}}" class="text-success">{{$data['data']->description}} | {{$data['data']->partNumber}} (<strong class="text-info">{{$data['data']->quantity}}</strong>)</option>
                                @foreach ($data['consigned'] as $consigned)
                                    <option value="{{$consigned->id}}"
                                            @if($data['data']->reference == $consigned->id)
                                            selected
                                        @endif>
                                        {{$consigned->description}} | <strong>{{$consigned->partNumber}}</strong> (<strong class="text-info">{{$consigned->actualQuantity}}</strong>)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Part Number
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="partNumber" class="form-control"
                                   value="{{$data['data']->partNumber}}" @if($data['data']->type == 'outgoing') readonly @endif>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Serial Number
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="serialNumber" class="form-control"
                                   value="{{$data['data']->serialNumber}}" @if($data['data']->type == 'outgoing') readonly @endif>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            System Type
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <select name="systemType" id="" class="form-control" @if($data['data']->type == 'outgoing') readonly @endif>
                                @foreach ($data['systemTypes'] as $systemType)
                                    <option value="{{$systemType['id']}}"
                                            @if($data['data']->systemType == $systemType['id'])
                                            selected
                                        @endif
                                    >{{$systemType['systemType']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Vendor
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="vendor" class="form-control"
                                   value="{{$data['data']->vendor}}" @if($data['data']->type == 'outgoing') readonly @endif>
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
                            <input type="number" name="quantity" id="actualQuantity" class="form-control"
                                   value="{{$data['data']->quantity}}" @if($data['data']->type == 'outgoing') readonly @endif>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
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
                            Date Received/Released
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
                            <input type="text" name="received_released_by" class="form-control"
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
                $('#invoiceNumber').show();
                $('#vendor').show();
                $('#purpose').hide();
                $('#rma').hide();
            }
            else
            {
                $('#purpose').show();
                $('#rma').show();
                $('#invoiceNumber').hide();
                $('#vendor').hide();
            }
            $('#movementType').click(function () {
                if($('#movementType').val() === 'incoming')
                {
                    $('#rmaval').val('');
                    $('#purposeval').val('');
                    $('#invoiceNumber').show();
                    $('#vendor').show();
                    $('#vendorval').val('{{$data['data']->vendor}}');
                    $('#purpose').hide();
                    $('#rma').hide();
                }
                else
                {
                    $('#purpose').show();
                    $('#vendor').hide();
                    $('#invoiceNumber').hide();
                    {{--$('#rmaval').val({{$data['data']->rma}});--}}
                    {{--$('#purposeval').val({{$data['data']->purpose}});--}}
                    $('#vendorval').val('');

                    if($('#purpose option:selected').text() === 'RMA')
                        $('#rma').show();
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
            $('#unitPrice, #quantity').on('blur', function () {
                var unitPrice = parseFloat($('#unitPrice').val());
                var quantity = parseInt($('#quantity').val());
                $('#totalPrice').val(unitPrice * quantity);
            })
        });
    </script>
@endsection
