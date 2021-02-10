@extends('layouts.main')
@section('title', 'Repairs')
@section('content2')
    <style>
        .modal-lg {
            max-width: 40%;
        }
    </style>
    <h2>Repair Search Result(s)</h2>
    {{--{{dd($_SESSION)}}--}}
    <a href="{{route('repairs')}}" class="btn btn-outline mt-1"><i class="fas fa-arrow-circle-left"></i> Back to Repairs</a>
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
            <strong>Woaah an Error!</strong> {{ Session::get('error')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(Session::has('response'))
        <div class="alert alert-success alert-dismissible fade show mt-1" role="alert">
            <strong>Success!</strong> {{ Session::get('response')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    {{--<input type="text" class="form-control my-2" id="unit-search" placeholder="Search here . . .">--}}

    <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
        <table class="table table-hover mt-2 border shadow-sm" id="reference-unit-table">
            <thead class="thead-dark ">
            <th>Customer ID</th>
            <th>Customer</th>
            <th>Description</th>
            <th>Total Board For Repair</th>
            <th>Total Repaired</th>
            <th>Unrepairable</th>
            <th>Status</th>
            <th></th>
            </thead>
            <tbody>
            @isset($data)
                @foreach (@$data as $repair)
                    <tr>
                        <td><a href="" id="view-trigger" data-id="{{$repair->id}}" data-type="all" data-toggle="modal" data-target="#exampleModal">{{$repair->customerID}}</a></td>
                        <td>{{$repair->customer}}</td>
                        <td>{{$repair->description}}</td>
                        <td><a href="" id="view-trigger" data-id="{{$repair->id}}" data-type="forRepair" data-toggle="modal" data-target="#exampleModal">{{$repair->boardsForRepair}}</a></td>
                        <td><a href="" id="view-trigger" data-id="{{$repair->id}}" data-type="repaired" data-toggle="modal" data-target="#exampleModal">{{$repair->totalRepaired}}</a></td>
                        <td><a href="" id="view-trigger" data-id="{{$repair->id}}" data-type="unrepairable" data-toggle="modal" data-target="#exampleModal">{{$repair->totalDefective}}</a></td>
                        <td>{{$repair->status}}</td>
                        <td>
                            <div class="float-right">
                                <div class="dropdown">
                                    <small class="dropdown-toggle" type="button" id="dropdownMenu2"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </small>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <a class="dropdown-item" href="{{route('repair.show', $repair->id)}}"
                                           type="button"><i class="fas fa-eye text-primary"></i> View</a>
                                        <a class="dropdown-item"
                                           href="{{route('repair.update' , $repair->id)}}"><i
                                                class="fas fa-edit "></i> Edit</a>
                                        <a class="dropdown-item" onclick="return confirm('Are you sure you want to delete?')"
                                           href="{{route('repair.destroy' , $repair->id)}}" type="button"><i
                                                class="fas fa-trash-alt text-danger"></i> Delete</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endisset
            </tbody>
        </table>
        <div class="p-2">
            {{$data->render()}}
        </div>
    </div>
    <div class="card">
        <div class="card-body">

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">RMA Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-2" id="rma-target"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function (){
            $('a[id="view-trigger"]').click(function () {
                $('#rma-target').html('');
                var id = $(this).attr('data-id');
                var type = $(this).attr('data-type');
                $.ajax({
                    url: '/api/repair/'+id+'/boards/'+type,
                    type: 'get',
                    success: function (data) {
                        var totalBoards = data.length;
                        $('#rma-target').append('<h6 class="text-info"><strong>'+ totalBoards +'</strong> Total Board(s) for this transaction</h6>');
                        data.forEach(function (val) {
                            if(val.serialNumber === null)
                                val.serialNumber = 'No Data';
                            if(val.dateReceived === null)
                                val.dateReceived = 'No Data';
                            $('#rma-target').append('' +
                                ' <ul class="list-group">' +
                                '<li class="list-group-item list-group-item-action mb-1">' +
                                '<div class="row">' +
                                '<div class="col-10">' +
                                ' <div class="row">' +
                                ' <div class="col-3">' +
                                '<small class="font-weight-bold">RMA</small>' +
                                '<div>' + val.rma + '</div>' +
                                '</div>' +
                                '<div class="col-3">' +
                                '<small class="font-weight-bold">Serial Number</small>' +
                                '<div>' + val.serialNumber + '</div>' +
                                '</div>' +
                                '<div class="col-3">' +
                                '<small class="font-weight-bold">Received Date</small>' +
                                '<div>' + val.dateReceived + '</div>' +
                                '</div>' +
                                '<div class="col-3">' +
                                '<small class="font-weight-bold">Status</small>' +
                                '<div>' + val.status + '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '')
                        })
                    }
                })
            });
        })
    </script>
@endsection
