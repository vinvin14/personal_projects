@extends('resources.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    {{--{{dd($data)}}--}}
    <a href="{{route('equipment.collection.show', $data['data']->partNumber)}}" class="btn btn-outline mt-1"><i class="fas fa-arrow-circle-left"></i> Go Back</a>
    <div class="row">
        <div class="col-6">
            <div class="card shadow-sm">
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
                            <strong>Success!</strong> {{ Session::get('response')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <h5 class="border-bottom">Equipment Information</h5>
                    <div class="row py-2">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                Description
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data['data']->description}}
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
                                {{$data['data']->partNumber ?? 'No Data'}}
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
                                {{$data['data']->serialNumber ?? 'No Data'}}
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
                                {{$data['data']->vendor ?? 'No Data'}}
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                Stored In
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data['data']->storedIn ?? 'No Data'}}
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                Location
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data['data']->location ?? 'No Data'}}
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
                                {{$data['data']->actualQuantity ?? 'No Data'}}
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
                                {{$data['data']->unitPrice ?? 'No Data'}}
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                Total Price
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data['data']->totalPrice ?? 'No Data'}}
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                Useful Life
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data['data']->usefulLife. ' year(s)' ?? 'No Data'}}
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-4 text-right">
                            <div class="font-weight-bold">
                                Depreciation Value
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data['data']->depreciationValue ?? 'No Data'}}
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
                                {{$data['data']->dateReceived ?? 'No Data'}}
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
                        <textarea name="" id="" cols="30" rows="10" class="form-control"
                                  readonly>{{$data['data']->remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                    <a href="{{route('equipment.update', $data['data']->id)}}" class="btn btn-success float-right mt-1">Update
                        Information</a>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="border-bottom">
                        Item Movement
                    </h5>
                    <div class="p-2" style="max-height: 700px !important; overflow-y: auto;">
                        <div class="font-weight-bold">Incoming</div>
                        @isset($data['incoming'])
                            @foreach($data['incoming'] as $incoming)
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-action mb-1">
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <small class="font-weight-bold">Invoice Number</small>
                                                        <div>{{$incoming->invoice_number}}</div>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="font-weight-bold">Quantity</small>
                                                        <div>{{$incoming->quantity}}</div>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="font-weight-bold">Transaction Date</small>
                                                        <div>{{$incoming->date_received}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2 ext-right my-3">
                                                <a href="{{route('movement.equipment.show', $incoming->id)}}"
                                                   class="">View</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            @endforeach
                    </div>
                    {{$data['incoming']->links()}}
                    @endisset
                    <div class="p-2" style="max-height: 700px !important; overflow-y: auto;">
                        <div class="font-weight-bold">Outgoing</div>
                        @isset($data['outgoing'])
                            @foreach($data['outgoing'] as $outgoing)
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-action mb-1">
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <small class="font-weight-bold">Purpose</small>
                                                        <div>{{$outgoing->purposeName}}</div>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="font-weight-bold">Released By</small>
                                                        <div>{{$outgoing->received_by}}</div>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="font-weight-bold">Received Date</small>
                                                        <div>{{$outgoing->date_received}}</div>
                                                    </div>
                                                </div>
                                            </div>['data]
                                            <div class="col-2 ext-right my-3">
                                                <a href="{{route('movement.equipment.show', $outgoing->id)}}"
                                                   class="">View</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            @endforeach
                    </div>
                    {{$data['outgoing']->links()}}
                    @endisset
                </div>
            </div>
        </div>
    </div>
@endsection
