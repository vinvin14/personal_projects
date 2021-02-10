@extends('movement.main')
@section('scripts')
    {{--<script src="{{asset('js/api.js')}}"></script>--}}
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
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
    <a href="{{route('movements.create', $reference_nav_selected)}}" class="btn btn-primary my-3 shadow">New Item Movement for {{$title}}</a>
    <div class="row">
        <div class="col-6">
            <div class="card shadow-sm">
                <div class="card-body" style="max-height: 600px !important; overflow-y: auto">
                    <h5 class="border-bottom">Incoming</h5>
                    @foreach($data['incoming'] as $incoming)
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action mb-1">
                                <div class="row">
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-3">
                                                <small class="font-weight-bold">Invoice Number</small>
                                                <div>{{$incoming->invoiceNumber}}</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="font-weight-bold">Vendor</small>
                                                <div>{{$incoming->vendor}}</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="font-weight-bold">Recipient</small>
                                                <div>{{$incoming->originName}}</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="font-weight-bold">Received Date</small>
                                                <div>{{$incoming->dateReceived}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2 ext-right my-3">
                                        <a href="{{route('movements.show', [$reference_nav_selected, $incoming->id])}}" class="t">View</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    @endforeach
                    {{$data['incoming']->links()}}
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow-sm" style="max-height: 600px !important; overflow-y: auto">
                <div class="card-body">
                    <h5 class="border-bottom">Outgoing</h5>
                    @foreach($data['outgoing'] as $outgoing)
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action mb-1">
                                <div class="row">
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-3">
                                                <small class="font-weight-bold">Purpose</small>
                                                <div>{{$outgoing->purposeName}}</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="font-weight-bold">Quantity</small>
                                                <div>{{$outgoing->quantity}}</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="font-weight-bold">Recipient</small>
                                                <div>{{$outgoing->originName}}</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="font-weight-bold">Received Date</small>
                                                <div>{{$outgoing->dateReceived}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2 ext-right my-3">
                                        <a href="{{route('movements.show', [$reference_nav_selected, $outgoing->id])}}" class="t">View</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    @endforeach
                    {{$data['outgoing']->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
