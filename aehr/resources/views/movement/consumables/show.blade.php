@extends('movement.main')
@section('scripts')
    {{--<script src="{{asset('js/api.js')}}"></script>--}}
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    {{--{{dd($data)}}--}}
    <a href="{{route('movement.consumables')}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Consumable Movements</a>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
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
                    <div class="row py-2">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                Movement Type
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                <span id="movementType">{{$data->type}}</span>
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
                                {{$data->invoice_number}}
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
                                {{$data->purpose ?? 'No Data'}}
                            </div>
                        </div>
                    </div>
                    {{--<div class="row py-2" id="outgoing-rma">--}}
                        {{--<div class="col-4 text-right">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--RMA--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data->rma ?? 'No Data'}}--}}
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
                                {{$data->partNumber}} | {{$data->description}}
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
                                {{$data->vendor}}
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
                                {{$data->brand}}
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
                                {{$data->quantity}}
                            </div>
                        </div>
                    </div>
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4 text-right">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Unit Price--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--${{$data->unitPrice}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4 text-right">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Total Price--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--${{$data->unitPrice * $data->quantity}}--}}
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
                                {{$data->date_received_released}}
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
                                {{$data->received_released_by}}
                            </div>
                        </div>
                    </div>
                    <div class="row p-3">
                        {{--<div class="col-4 text-right">--}}

                        {{--</div>--}}
                        <div class="col-12 text-left">
                            <div class="">
                                <div class="font-weight-bold">
                                    Remarks
                                </div>
                                <textarea name="" id="" cols="30" rows="10" class="form-control" readonly>{{$data->remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-3">
                        <a href="{{route('movement.consumable.update', $data->id)}}" class="btn btn-success p-2 float-right">Update Movement</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $(document).ready(function () {
                var type = $('#movementType').text();
                if(type === 'incoming')
                {
                    $('#incoming-invoicenumber').show();
                    $('#incoming-vendor').show();
                    $('#outgoing-purpose').hide();
                    $('#outgoing-rma').hide();
                }
                else
                {
                    $('#incoming-invoicenumber').hide();
                    $('#incoming-vendor').hide();
                    $('#outgoing-purpose').show();
                    $('#outgoing-rma').show();
                }
            });
        });
    </script>
@endsection
