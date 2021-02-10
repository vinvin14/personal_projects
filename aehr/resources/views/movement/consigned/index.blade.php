@extends('movement.main')
@section('scripts')
    {{--<script src="{{asset('js/api.js')}}"></script>--}}
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    {{--{{dd($data)}}--}}
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
            <strong>Woaah an Error!</strong>  {{ Session::get('error')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    {{--{{dd($data)}}--}}
    @if(Session::has('response'))
        <div class="alert alert-success alert-dismissible fade show mt-1" role="alert">
            <strong>Well done!</strong>  {{ Session::get('response')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    {{--{{dd($data)}}--}}
    <a href="{{route('movement.consigned.create')}}" class="btn btn-primary my-2 shadow">Item Movement for Consigned Spares</a>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="incoming-tab" data-toggle="tab" href="#incoming" role="tab" aria-controls="incoming" aria-selected="true">Incoming</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="outgoing-tab" data-toggle="tab" href="#outgoing" role="tab" aria-controls="outgoing" aria-selected="false">Outgoing</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="incoming" role="tabpanel" aria-labelledby="incoming-tab">
            <h5 class="border-bottom mt-3">Incoming Movement(s)</h5>
            <div class="table-responsive" style="max-height: 400px !important; overflow-y: auto">
                <table class="table table-bordered shadow-sm">
                    <thead class="thead-dark">
                    <th>Invoice #</th>
                    <th>Vendor</th>
                    <th>Consigned Spare</th>
                    <th>Received Date</th>
                    <th>Received By</th>
                    <th></th>
                    </thead>
                    <tbody>
                    @foreach($data['incoming'] as $incoming)
                        <tr>
                            <td>{{$incoming->invoice_number}}</td>
                            <td>{{$incoming->vendor}}</td>
                            <td>{{ucfirst($incoming->description)}} | {{strtoupper($incoming->partNumber)}} | {{strtoupper($incoming->serialNumber)}}</td>
                            <td>{{$incoming->date_received_released}}</td>
                            <td>{{$incoming->received_released_by}}</td>
                            <td>
                                <div class="float-right">
                                    <div class="dropdown">
                                        <small class="dropdown-toggle" type="button" id="dropdownMenu2"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </small>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                            <a class="dropdown-item" href="{{route('movement.consigned.show', $incoming->id)}}"
                                               type="button"><i class="fas fa-eye text-primary"></i> View</a>
                                            <a class="dropdown-item"
                                               href="{{route('movement.consigned.update' , $incoming->id)}}"><i
                                                    class="fas fa-edit "></i> Edit</a>
                                            <a class="dropdown-item" onclick="return confirm('Are you sure you want to revert this movement?')"
                                               href="{{route('movement.consigned.revert' , $incoming->id)}}" type="button"><i class="fas fa-recycle"></i> Revert</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="outgoing" role="tabpanel" aria-labelledby="outgoing-tab">
            <h5 class="border-bottom mt-3">Outgoing Movement(s)</h5>
            <div class="table-responsive" style="max-height: 400px !important; overflow-y: auto">
                <table class="table table-bordered shadow-sm">
                    <thead class="thead-dark">
                    <th>Consigned Spare</th>
                    <th>Purpose</th>
                    <th>Released Date</th>
                    <th>Released By</th>
                    <th></th>
                    </thead>
                    <tbody>
                    @foreach($data['outgoing'] as $outgoing)
                        <tr>
                            <td>{{ucfirst($outgoing->description)}} | {{strtoupper($outgoing->partNumber)}} | {{strtoupper($outgoing->serialNumber)}}</td>
                            <td>{{$outgoing->purposeName}}</td>
                            <td>{{$outgoing->date_received_released}}</td>
                            <td>{{$outgoing->received_released_by}}</td>
                            <td>
                                <div class="float-right">
                                    <div class="dropdown">
                                        <small class="dropdown-toggle" type="button" id="dropdownMenu2"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </small>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                            <a class="dropdown-item" href="{{route('movement.consigned.show', $outgoing->id)}}"
                                               type="button"><i class="fas fa-eye text-primary"></i> View</a>
                                            <a class="dropdown-item"
                                               href="{{route('movement.consigned.update' , $outgoing->id)}}"><i
                                                    class="fas fa-edit "></i> Edit</a>
                                            <a class="dropdown-item" onclick="return confirm('Are you sure you want to revert this movement?')"
                                               href="{{route('movement.consigned.revert' , $outgoing->id)}}" type="button"><i class="fas fa-recycle"></i> Revert</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{--<div class="row">--}}
        {{--<div class="col-6">--}}
            {{--<div class="card shadow-sm">--}}
                {{--<div class="card-body" style="max-height: 600px !important; overflow-y: auto">--}}
                    {{--<h5 class="border-bottom">Incoming</h5>--}}
                    {{--@foreach($data['incoming'] as $incoming)--}}
                        {{--<ul class="list-group">--}}
                            {{--<li class="list-group-item list-group-item-action mb-1">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-10">--}}
                                        {{--<div class="row">--}}
                                            {{--<div class="col-3">--}}
                                                {{--<small class="font-weight-bold">Invoice Number</small>--}}
                                                {{--<div>{{$incoming->invoice_Number}}</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-3">--}}
                                                {{--<small class="font-weight-bold">Vendor</small>--}}
                                                {{--<div>{{$incoming->vendor}}</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-3">--}}
                                                {{--<small class="font-weight-bold">Recipient</small>--}}
                                                {{--<div>{{$incoming->originName}}</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-3">--}}
                                                {{--<small class="font-weight-bold">Received Date</small>--}}
                                                {{--<div>{{$incoming->date_received}}</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-2 ext-right my-3">--}}
                                        {{--<a href="{{route('movements.show', [$reference_nav_selected, $incoming->id])}}" class="t">View</a>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--@endforeach--}}
                    {{--{{$data['incoming']->links()}}--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-6">--}}
            {{--<div class="card shadow-sm" style="max-height: 600px !important; overflow-y: auto">--}}
                {{--<div class="card-body">--}}
                    {{--<h5 class="border-bottom">Outgoing</h5>--}}
                    {{--@foreach($data['outgoing'] as $outgoing)--}}
                        {{--<ul class="list-group">--}}
                            {{--<li class="list-group-item list-group-item-action mb-1">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-10">--}}
                                        {{--<div class="row">--}}
                                            {{--<div class="col-6">--}}
                                                {{--<small class="font-weight-bold">Reference</small>--}}
                                                {{--<div>{{$outgoing->description}} | {{$outgoing->partNumber}} | {{$outgoing->serialNumber}}</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-3">--}}
                                                {{--<small class="font-weight-bold">Purpose</small>--}}
                                                {{--<div>{{$outgoing->purposeName}}</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-3">--}}
                                                {{--<small class="font-weight-bold">Released Date</small>--}}
                                                {{--<div>{{$outgoing->date_received}}</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-2 ext-right my-3">--}}
                                        {{--<a href="{{route('movement.equipment.show', $outgoing->id)}}" class="t">View</a>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--@endforeach--}}
                    {{--{{$data['outgoing']->links()}}--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection
