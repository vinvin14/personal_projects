@extends('movement.main')
@section('scripts')
    {{--<script src="{{asset('js/api.js')}}"></script>--}}
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    {{--{{dd($data)}}--}}
    <a href="{{route('movement.consigned')}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Consigned Spare Movements</a>
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
                    <div class="row py-2" id="purpose">
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
                    <div class="row py-2" id="incoming-invoicenumber">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                Invoice Number
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data->invoice_number ?? 'No Data'}}
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                Reference
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data->description}}
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
                                {{$data->partNumber}}
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
                                {{$data->serialNumber}}
                            </div>
                        </div>
                    </div>
                    <div class="row py-2" id="vendor">
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
                    <div class="row py-2">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                System Type
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data->systemTypeName}}
                            </div>
                        </div>
                    </div>
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4 text-right">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Board Type--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data->boardType}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
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
                    <div class="row py-2">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                Unit Price
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                ${{$data->unitPrice}}
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
                                {{$data->date_received_released}}
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                Responsible Person
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data->received_released_by}}
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
                                <textarea name="" id="" cols="30" rows="10" class="form-control" readonly>{{$data->remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                    <a href="{{route('movement.consigned.update', $data->id)}}" class="btn btn-success py-2 float-right">Update Movement</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var type = $('#movementType').text();
            var purpose = $('#purpose').text();
            if(type === 'incoming')
            {
                $('#incoming-invoicenumber').show();
                $('#vendor').show();
                $('#purpose').hide();
                $('#rma').hide();
            }
            else
            {
                $('#purpose').show();
                $('#rma').show();
                $('#incoming-invoicenumber').hide();
                $('#vendor').hide();
            }
            if(purpose === 'RMA')
            {
                $('#rma').show();
            }
            else
            {
                $('#rma').hide();
            }
        });
    </script>
@endsection
