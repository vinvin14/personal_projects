@extends('layouts.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('title', 'Show Board Type')
@section('content2')
    <style>
        .modal-lg {
            max-width: 60%;
        }
    </style>
    <a href="{{route('repairs')}}" class="btn btn-outline mt-1"><i class="fas fa-arrow-circle-left"></i> Back to Repairs</a>
    <div class="row">
        <div class="col-4">
            <div class="card mb-3">
                <div class="card-body shadow-sm">
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
                    <h5 class="border-bottom">{{$data['repair']->description}}</h5>
                    <div class="row py-2">
                        <div class="col-4  ">
                            <div class="font-weight-bold">
                                Description
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data['repair']->description}}
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-4">
                            <div class="font-weight-bold">
                                Customer
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="text-info">
                                {{$data['repair']->customer}}
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-4  ">
                            <div class="font-weight-bold">
                                Total Job
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data['repair']->totalJob ?? 'No Data yet'}}
                            </div>
                        </div>
                    </div>
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Total RMA Recorded--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data['repair']->totalBoardsRecorded ?? 'No Data yet'}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="row py-2">
                        <div class="col-4  ">
                            <div class="font-weight-bold">
                                Total Repaired
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data['repair']->totalRepaired ?? 'No Data yet'}}
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-4  ">
                            <div class="font-weight-bold">
                                Total Defective
                            </div>
                        </div>
                        <div class="col-8 text-left">
                            <div class="text-info">
                                {{$data['repair']->totalDefective ?? 'No Data yet'}}
                            </div>
                        </div>
                    </div>
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Transaction Date--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data['repair']->transactionDate}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Received By--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data['repair']->receivedBy}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Status--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data['repair']->status}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Ship to Customer id--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data['repair']->shipToCustomerName ?? 'No Data yet'}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Ship Date--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data['repair']->shipDate ?? 'No Data yet'}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Ship Address--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data['repair']->address ?? 'No Data yet'}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Incoming Tracking--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data['repair']->incomingTracking ?? 'No Data yet'}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Outgoing Tracking--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data['repair']->outgoingTracking ?? 'No Data yet'}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Contact Person--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data['repair']->contactPerson ?? 'No Data yet'}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row py-2">--}}
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}
                                {{--Contact Number--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-8 text-left">--}}
                            {{--<div class="text-info">--}}
                                {{--{{$data['repair']->contactNumber ?? 'No Data yet'}}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="row py-2">
                        {{--<div class="col-4  ">--}}
                            {{--<div class="font-weight-bold">--}}

                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="col-12 text-left">
                            <div class="font-weight-bold">Remarks</div>
                            <div class="text-info">
                                <textarea cols="30" class="form-control" rows="10"
                                          readonly>{{$data['repair']->remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                    <a href="{{route('repair.update', $data['repair']->id)}}" class="btn btn-success p-2 float-right">Update
                        Repair</a>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card card-body shadow-sm">
                <div style="overflow-y: auto; max-height: 600px !important;">
                    <div class="font-weight-bold">Existing Board(s)</div>
                    <a href="{{route('board.create', $data['repair']->id)}}" class="btn btn-primary my-2"> Record
                        RMA</a>
                    @isset($data['boards'])
                        @foreach($data['boards'] as $board)
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-action mb-1">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="row">
                                                <div class="col-4">
                                                    <small class="font-weight-bold">RMA</small>
                                                    <div>{{$board->rma}}</div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="font-weight-bold">Serial Number</small>
                                                    <div>{{$board->serialNumber}}</div>
                                                </div>
                                                <div class="col-4">
                                                    <small class="font-weight-bold">Job Status</small>
                                                    <div>{{$board->status}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 ext-right my-3">
                                            <a href="" id="view-trigger" data-id="{{$board->id}}" data-toggle="modal" data-target="#rma-modal"
                                               class="mr-3"><i class="fas fa-eye text-primary"></i></a>
                                            <a href="{{route('board.update', [$data['repair']->id, $board->id])}}" class="mr-3"><i class="fas fa-edit text-dark"></i></a>
                                            <a href="{{route('board.print', [$data['repair']->id, $board->id])}}" target="_blank"><i class="fas fa-print"></i></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="rma-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">RMA Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">RMA</small>
                            <input type="text" id="rma" id="rma" class="form-control" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Description</small>
                            <input type="text" class="form-control" id="description" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Serial Number</small>
                            <input type="text" class="form-control" id="serialNumber" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Part Number</small>
                            <input type="text" class="form-control" id="partNumber" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">System Type</small>
                            <input type="text" class="form-control" id="systemType" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Operating System</small>
                            <input type="text" class="form-control" id="operatingSystem" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Slot</small>
                            <input type="text" class="form-control" id="slot" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Stored In</small>
                            <input type="text" class="form-control" id="storedIn" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Location</small>
                            <input type="text" class="form-control" id="location" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Fault Code</small>
                            <input type="text" class="form-control" id="faultCode" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Fault Details</small>
                            <textarea id="faultDetails" cols="1" rows="1" class="form-control" readonly></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Date Received</small>
                            <input type="date" id="dateReceived" class="form-control" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Incoming Tracking #</small>
                            <input type="text" class="form-control" id="incomingTrackingNumber" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Received By</small>
                            <input type="text" id="receivedBy" class="form-control" readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-3">
                            <small class="font-weight-bold">Cause of Fault</small>
                            <input type="text" class="form-control" id="causeOfFC" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Cause of Fault Details</small>
                            <textarea id="causeOfFCDetails" cols="1" rows="1" class="form-control" readonly></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Start of Repair</small>
                            <input type="date" id="startOfRepair" id="" class="form-control" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">End of Repair</small>
                            <input type="date" class="form-control" id="endOfRepair" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Turn Around Time</small>
                            <input type="text" id="turnAroundTime" class="form-control" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Repaired By</small>
                            <input type="text" id="fe" class="form-control" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Type of Service</small>
                            <input type="text" id="typeOfService" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Findings</small>
                            <textarea id="findings" cols="1" rows="2" class="form-control" readonly></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">EWS Findings</small>
                            <textarea id="EWSFindings" cols="1" rows="2" class="form-control" readonly></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Bench Test Findings</small>
                            <textarea id="benchTestFindings" cols="1" rows="2" class="form-control" readonly></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Work Performed</small>
                            <textarea id="workPerformed" cols="1" rows="2" class="form-control" readonly></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Test Time (Hours)</small>
                            <input type="number" class="form-control" id="testTime" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Repair Time (Hours)</small>
                            <input type="number" class="form-control" id="repairTime" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Upgrade Time (Hours)</small>
                            <input type="number" class="form-control" id="upgradeTime" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Status</small>
                            <input type="text" class="form-control" id="status" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Reason for Return</small>
                            <textarea id="reasonForReturn"  cols="1" rows="1" class="form-control" readonly></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Remarks</small>
                            <textarea id="remarks" cols="1" rows="1" class="form-control" readonly></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Ship To Customer Name</small>
                            <input type="text" class="form-control" id="shipToCustomerName" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Address</small>
                            <textarea id="address" cols="1" rows="1" class="form-control" readonly></textarea>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Outgoing Tracking #</small>
                            <input type="text" class="form-control" id="outgoingTrackingNumber" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Date Shipped</small>
                            <input type="date" class="form-control" id="dateShipped" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Contact Person</small>
                            <input type="text" class="form-control" id="contactPerson" readonly>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Contact #</small>
                            <input type="text" class="form-control" id="contactNumber" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('a[id="view-trigger"]').click(function () {
            var id = $(this).attr('data-id');
            $.ajax({
               url: '/api/board/'+id,
               type: 'get',
               success: function (data)
               {
                   console.log(data);
                   $('#rma').val(data.rma)
                   $('#description').val(data.description)
                   $('#serialNumber').val(data.serialNumber)
                   $('#partNumber').val(data.partNumber)
                   $('#systemType').val(data.systemType)
                   $('#operatingSystem').val(data.operatingSystem)
                   $('#slot').val(data.slot)
                   $('#storedIn').val(data.storedIn)
                   $('#location').val(data.location)
                   $('#faultCode').val(data.faultCode)
                   $('#faultDetails').val(data.faultDetails)
                   $('#dateReceived').val(data.dateReceived)
                   $('#incomingTrackingNumber').val(data.incomingTrackingNumber)
                   $('#receivedBy').val(data.receivedBy)
                   $('#causeOfFC').val(data.causeOfFC)
                   $('#causeOfFCDetails').val(data.causeOfFCDetails)
                   $('#startOfRepair').val(data.startOfRepair)
                   $('#endOfRepair').val(data.endOfRepair)
                   $('#turnAroundTime').val(data.turnAroundTime)
                   if(data.fe !== null)
                       $('#fse').val(data.lastname+', '+data.firstname+' '+data.middlename[0]+'.')
                   else
                       $('#fse').val('')
                   $('#typeOfService').val(data.typeOfService)
                   $('#findings').val(data.findings)
                   $('#EWSFindings').val(data.EWSFindings)
                   $('#benchTestFindings').val(data.benchTestFindings)
                   $('#workPerformed').val(data.workPerformed)
                   $('#testTime').val(data.testTime)
                   $('#repairTime').val(data.repairTime)
                   $('#upgradeTime').val(data.upgradeTime)
                   $('#status').val(data.status)
                   $('#reasonForReturn').val(data.reasonForReturn)
                   $('#remarks').val(data.remarks)
                   $('#shipToCustomerName').val(data.shipToCustomerName)
                   $('#address').val(data.address)
                   $('#outgoingTrackingNumber').val(data.outgoingTrackingNumber)
                   $('#dateShipped').val(data.dateShipped)
                   $('#contactPerson').val(data.contactPerson)
                   $('#contactNumber').val(data.contactNumber)
               },
               fail: function (data) {
                   console.log(data);
               }
            });
        });
    </script>
@endsection
