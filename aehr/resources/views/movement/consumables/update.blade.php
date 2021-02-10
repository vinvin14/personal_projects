@extends('movement.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    {{--{{dd($data)}}--}}
    <a href="{{route('movement.consumables')}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Consumable Movements</a>
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
            <form action="{{route('movement.consumable.upsave', $data['data']->id)}}" method="post">
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
                                <option value="incoming" @if($data['data']->type == 'incoming') selected @endif>Incoming</option>
                                <option value="outgoing" @if($data['data']->type == 'outgoing') selected @endif>Outgoing</option>
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
                            <select name="purpose" id="purposeval" class="form-control">
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
                {{--<div class="row py-2" id="rma">--}}
                    {{--<div class="col-4 text-right">--}}
                        {{--<div class="font-weight-bold">--}}
                            {{--RMA--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-8 text-left">--}}
                        {{--<div class="text-info">--}}
                            {{--<select name="rma" id="rmaval" class="form-control">--}}
                                {{--<option value="">-</option>--}}
                                {{--@foreach($data['boards'] as $board)--}}
                                    {{--<option value="{{$board->id}}"--}}
                                            {{--@if($data['data']->rma == $board->id)--}}
                                            {{--selected--}}
                                        {{--@endif--}}
                                    {{-->{{$board->rma}}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Consumable
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <select name="reference" id="" class="form-control" required>
                                <option value="">-</option>
                                @foreach ($data['consumables'] as $consumable)
                                    <option value="{{$consumable->id}}"
                                        @if($data['data']->reference == $consumable->id)
                                            selected
                                        @endif
                                    >{{$consumable->description}} -
                                        <strong>{{$consumable->partNumber}}</strong>
                                        (<strong class="text-info">{{$consumable->actualQuantity}}</strong>)</option>
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
                            <input type="text" name="vendor" id="vendor" class="form-control"
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
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="brand" id="brand" class="form-control"
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
                {{--<div class="row py-2">--}}
                    {{--<div class="col-4 text-right">--}}
                        {{--<div class="font-weight-bold">--}}
                            {{--Total Price--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-8 text-left">--}}
                        {{--<div class="text-info">--}}
                            {{--<input type="number" step=".01" name="totalPrice" id="totalPrice" class="form-control"--}}
                                   {{--value="{{$data['data']->unitPrice * $data['data']->quantity}}" readonly>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
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
                $('#outgoing-purpose').hide();
            }
            else
            {
                $('#incoming-invoicenumber').hide();
                $('#incoming-vendor').hide();
                $('#outgoing-purpose').show();
            }
            $('#movementType').click(function () {
                if($('#movementType').val() === 'incoming')
                {
                    $('#incoming-invoicenumber').show();
                    $('#incoming-vendor').show();
                    $('#incoming-brand').show();
                    $('#outgoing-purpose').hide();
                }
                else
                {
                    $('#outgoing-purpose').show();
                    $('#incoming-vendor').hide();
                    $('#incoming-brand').hide();
                    $('#incoming-invoicenumber').hide();

                }
            });
        });
    </script>
@endsection
