@extends('layouts.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('title', 'Create RMA')
@section('content2')
    <style>
        .modal-lg {
            max-width: 50%;
        }
    </style>
    <a href="{{route('repair.show', $data['motherRecord'])}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Mother Repair Record</a>
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
            <h5 class="border-bottom">Create New RMA</h5>
                <form action="{{route('board.store', $data['motherRecord'])}}" method="post">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">RMA</small>
                            <input type="text" name="rma" id="rma" class="form-control" required>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Description</small>
                            <input type="text" class="form-control" name="description" required>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Serial Number</small>
                            <input type="text" class="form-control" name="serialNumber" required>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Part Number</small>
                            <input type="text" class="form-control" name="partNumber" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">System Type</small>
                            <select name="systemType" id="" class="form-control">
                                <option value="">-</option>
                                @foreach ($data['systemTypes'] as $systemType)
                                    <option value="{{$systemType['id']}}">{{$systemType['systemType']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Operating System</small>
                            <input type="text" class="form-control" name="operatingSystem">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Slot</small>
                            <input type="text" class="form-control" name="slot">
                        </div>
                        {{--<div class="col-3">--}}
                            {{--<small class="font-weight-bold">Cause of Fault Code Details</small>--}}
                            {{--<textarea name="causeOfFCDetails" id="" cols="" rows="1" class="form-control"></textarea>--}}
                        {{--</div>--}}
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Stored In</small>
                            <select name="storedIn" id="" class="form-control">
                                <option value="">-</option>
                                @foreach ($data['locations'] as $location)
                                    <option value="{{$location['id']}}">{{$location['location']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Location</small>
                            <input type="text" class="form-control" name="location">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Fault Code</small>
                            <select name="faultCode" id="" class="form-control">
                                <option value="">-</option>
                                @foreach ($data['faultCodes'] as $faultCode)
                                    <option value="{{$faultCode['id']}}">{{$faultCode['code']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Fault Details</small>
                            <textarea name="faultDetails" id="" cols="" rows="1" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Date Received</small>
                            <input type="date" name="dateReceived" class="form-control" required>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Incoming Tracking #</small>
                            <input type="text" class="form-control" name="incomingTrackingNumber">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Received By</small>
                            <input type="text" name="receivedBy" class="form-control">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-3">
                            <small class="font-weight-bold">Cause of Fault</small>
                            <input type="text" class="form-control" name="causeOfFC">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Cause of Fault Details</small>
                            <textarea name="causeOfFCDetails" id="" cols="" rows="1" class="form-control"></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Start of Repair</small>
                            <input type="date" name="startOfRepair" id="" class="form-control">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">End of Repair</small>
                            <input type="date" class="form-control" name="endOfRepair">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Turn Around Time</small>
                            <input type="text" name="turnAroundTime" class="form-control">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Repaired By</small>
                            <select name="fe" id="" class="form-control">
                                <option value="">-</option>
                                <option>-</option>
                                @foreach($data['fse'] as $fe)
                                    <option value="{{$fe['id']}}">{{$fe['lastname']}}, {{$fe['firstname']}} {{$fe['middlename'][0]}}.</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Type of Service</small>
                            <select name="typeOfService" id="" class="form-control">
                                <option value="">-</option>
                                @foreach($data['typeOfServices'] as $typeOfService)
                                    <option value="{{$typeOfService['id']}}">{{$typeOfService['typeOfService']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Findings</small>
                            <textarea name="findings" id="" cols="1" rows="2" class="form-control"></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">EWS Findings</small>
                            <textarea name="EWSFindings" id="" cols="1" rows="2" class="form-control"></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Bench Test Findings</small>
                            <textarea name="benchTestFindings" id="" cols="1" rows="2" class="form-control"></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Work Performed</small>
                            <textarea name="workPerformed" id="" cols="1" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Test Time (Hours)</small>
                            <input type="number" class="form-control" name="testTime">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Repair Time (Hours)</small>
                            <input type="number" class="form-control" name="repairTime">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Upgrade Time (Hours)</small>
                            <input type="number" class="form-control" name="upgradeTime">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Status</small>
                            <select name="status" id="" class="form-control">
                                <option value="">-</option>
                                @foreach($data['status'] as $status)
                                    <option value="{{$status->id}}">{{$status->status}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Reason for Return</small>
                            <textarea name="reasonForReturn" id="" cols="1" rows="1" class="form-control"></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Remarks</small>
                            <textarea name="remarks" id="" cols="1" rows="1" class="form-control"></textarea>
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Ship To Customer Name</small>
                            <input type="text" class="form-control" name="shipToCustomerName">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Address</small>
                            <textarea name="address" id="" cols="1" rows="1" class="form-control"></textarea>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <small class="font-weight-bold">Outgoing Tracking #</small>
                            <input type="text" class="form-control" name="outgoingTrackingNumber">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Date Shipped</small>
                            <input type="date" class="form-control" name="dateShipped">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Contact Person</small>
                            <input type="text" class="form-control" name="contactPerson">
                        </div>
                        <div class="col-3">
                            <small class="font-weight-bold">Contact #</small>
                            <input type="text" class="form-control" name="contactNumber">
                        </div>
                    </div>
                    <button class="btn btn-success mt-2">Record RMA</button>
                </form>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Replacement Part(s)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row p-3">
                        <div class="col-6">
                            <label for="">Replacement Part(s)</label>
                            <select name="" id="components" class="form-control">
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="">Quantity to be used</label>
                            <input type="number" id="quantity" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="add-replacement" class="btn btn-primary">Add Part(s)</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // $.ajax({
            //     url: '/api/resources/components',
            //     type: 'GET',
            //     statusCode: {
            //         200: function (data) {
            //             $('#components').html('');
            //             $('#components').append('<option value="">-</option>')
            //             data.forEach(function (val) {
            //                 $('#components').append('<option value="'+val.id+'">'+val.description+' - ' +val.partNumber + ' ( ' +val.actualQuantity +')</option>');
            //             });
            //             $('#add-replacement').click(function (){
            //                 var id = $('#components').val();
            //                 var quantity = $('#quantity').val();
            //
            //                 var data = $.ajax({
            //                     url: '/api/component/' +id,
            //                     type: 'get',
            //                     success: function (data) {
            //
            //                     }
            //                 });
            //             });
            //         }
            //     }
            // });
        });
    </script>
@endsection
