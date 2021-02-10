@extends('resources.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    {{--{{dd($data)}}--}}
    <a href="{{route('consigned')}}" class="btn btn-outline mt-1"><i class="fas fa-arrow-circle-left"></i> Back to Consigned</a>
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
            <strong>Congratulations!</strong> {{ Session::get('response')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <h3>Search Result(s) for Consigned Spare</h3>
    <small class="text-info font-italic font-weight-bold">You have <strong>{{$total_results}}</strong> total result(s)</small>
    <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
        <table class="table table-hover mt-2 border shadow-sm" id="reference-unit-table">
            <thead class="thead-dark ">
            <th>Part Number</th>
            <th>Description</th>
            <th>Serial Number</th>
            <th>Vendor</th>
            <th>System Type</th>
            <th>Stored In</th>
            <th>Location</th>
            <th>Date Received</th>
            <th>Unit Price</th>
            <th>Depreciation Value</th>
            <th></th>
            </thead>
            <tbody>
            @isset($data)
                @foreach (@$data as $consigned)
                    <tr>
                        <td>{{$consigned->partNumber}}</td>
                        <td>{{$consigned->description}}</td>
                        <td>{{$consigned->serialNumber}}</td>
                        <td>{{$consigned->vendor}}</td>
                        <td>{{$consigned->systemType}}</td>
                        <td>{{$consigned->storedIn}}</td>
                        <td>{{$consigned->location}}</td>
                        <td>{{$consigned->dateReceived}}</td>
                        <td>${{number_format($consigned->unitPrice, 2)}}</td>
                        <td>${{number_format($consigned->depreciationValue, 2)}}</td>
                        <td>
                            <div class="float-right">
                                <div class="dropdown">
                                    <small class="dropdown-toggle" type="button" id="dropdownMenu2"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </small>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <a class="dropdown-item" href="{{route('consigned.show', $consigned->id)}}?partnum={{$consigned->partNumber}}"
                                           type="button"><i class="fas fa-eye text-primary"></i> View</a>
                                        <a class="dropdown-item"
                                           href="{{route('consigned.update' , $consigned->id)}}?partnum={{$consigned->partNumber}}" type="button"><i
                                                class="fas fa-edit "></i> Edit</a>
                                        <a class="dropdown-item" onclick="return confirm('Are you sure you want to delete?')"
                                           href="{{route('consigned.destroy' , $consigned->id)}}?partnum={{$consigned->partNumber}}" type="button"><i
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
    </div>
    {{--<!-- Modal -->--}}
    {{--<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
        {{--<div class="modal-dialog modal-lg" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<h5 class="modal-title" id="exampleModalLabel">Consigned Spare List</h5>--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                        {{--<span aria-hidden="true">&times;</span>--}}
                    {{--</button>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<div class="table-responsive">--}}
                        {{--<table class="table table-bordered">--}}
                            {{--<thead class="">--}}
                            {{--<th>Part Number</th>--}}
                            {{--<th>Serial Number</th>--}}
                            {{--<th>Vendor</th>--}}
                            {{--<th>System Type</th>--}}
                            {{--<th>Board Type</th>--}}
                            {{--<th>Stored In</th>--}}
                            {{--<th>Location</th>--}}
                            {{--<th>Date Received</th>--}}
                            {{--<th>Unit Price</th>--}}
                            {{--<th>Depreciation Value</th>--}}
                            {{--</thead>--}}
                            {{--<tbody id="consigned-target">--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <script>
        $('a[id="view-trigger"]').click(function () {
            var partnum = $(this).attr('data-partnum');
            $.ajax({
                url: '/api/resources/consigned/'+partnum,
                type: 'get',
                success:function (data) {
                    console.log(data)
                    $('#consigned-target').html('');
                    data.forEach(function (val) {
                        $('#consigned-target').append('' +
                            '<tr>' +
                            '<td>' + val.partNumber + '</td>' +
                            '<td>' + val.serialNumber + '</td>' +
                            '<td>' + val.vendor + '</td>' +
                            '<td>' + val.systemType + '</td>' +
                            // '<td>' + val.boardType + '</td>' +
                            '<td>' + val.storedIn + '</td>' +
                            '<td>' + val.location + '</td>' +
                            '<td>' + val.dateReceived + '</td>' +
                            '<td>' + val.unitPrice + '</td>' +
                            '<td>' + val.depreciationValue + '</td>' +
                            '</tr>');
                    });
                }
            })
        });
    </script>
@endsection
