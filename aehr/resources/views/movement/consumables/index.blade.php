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
    <a href="{{route('movement.consumable.create')}}" class="btn btn-primary my-2 shadow">Item Movement for Consumable</a>
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
                    <th>Consumable</th>
                    <th>Quantity</th>
                    <th>Received Date</th>
                    <th>Received By</th>
                    <th></th>
                    </thead>
                    <tbody>
                    @foreach($data['incoming'] as $incoming)
                        <tr>
                            <td>{{$incoming->invoice_number}}</td>
                            <td>{{ucfirst($incoming->description)}} | {{strtoupper($incoming->partNumber)}}</td>
                            <td>{{number_format($incoming->quantity)}}</td>
                            <td>{{$incoming->date_received_released}}</td>
                            <td>{{$incoming->received_released_by}}</td>
                            <td>
                                <div class="float-right">
                                    <div class="dropdown">
                                        <small class="dropdown-toggle" type="button" id="dropdownMenu2"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </small>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                            <a class="dropdown-item" href="{{route('movement.consumable.show', $incoming->id)}}"
                                               type="button"><i class="fas fa-eye text-primary"></i> View</a>
                                            <a class="dropdown-item"
                                               href="{{route('movement.consumable.update' , $incoming->id)}}"><i
                                                    class="fas fa-edit "></i> Edit</a>
                                            <a class="dropdown-item" onclick="return confirm('Are you sure you want to revert this movement?')"
                                               href="{{route('movement.consumable.revert' , $incoming->id)}}" type="button"><i class="fas fa-recycle"></i> Revert</a>
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
                    <th>Consumable</th>
                    <th>Purpose</th>
                    <th>Quantity</th>
                    <th>Released Date</th>
                    <th>Released By</th>
                    <th></th>
                    </thead>
                    <tbody>
                    @foreach($data['outgoing'] as $incoming)
                        <tr>
                            <td>{{ucfirst($incoming->description)}} | {{strtoupper($incoming->partNumber)}}</td>
                            <td>{{$incoming->purposeName}}</td>
                            <td>{{$incoming->quantity}}</td>
                            <td>{{$incoming->date_received_released}}</td>
                            <td>{{$incoming->received_released_by}}</td>
                            <td>
                                <div class="float-right">
                                    <div class="dropdown">
                                        <small class="dropdown-toggle" type="button" id="dropdownMenu2"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </small>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                            <a class="dropdown-item" href="{{route('movement.consumable.show', $incoming->id)}}"
                                               type="button"><i class="fas fa-eye text-primary"></i> View</a>
                                            <a class="dropdown-item"
                                               href="{{route('movement.consumable.update' , $incoming->id)}}"><i
                                                    class="fas fa-edit "></i> Edit</a>
                                            <a class="dropdown-item" onclick="return confirm('Are you sure you want to revert this movement?')"
                                               href="{{route('movement.consumable.revert' , $incoming->id)}}" type="button"><i class="fas fa-recycle"></i> Revert</a>
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
@endsection
