@extends('layouts.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('title', 'Update RMA')
@section('content2')
    <style>
        .modal-lg {
            max-width: 50%;
        }
    </style>
    <a href="{{route('repair.show', $data['data']->motherRecord)}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Go Back</a>
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
            <h5 class="border-bottom">Update RMA</h5>
            <form action="{{route('board.upsave', [$data['data']->motherRecord, $data['data']->id])}}" method="post">
                @csrf
                <div class="row mt-3">
                    <div class="col-3">
                        <small class="font-weight-bold">RMA</small>
                        <input type="text" name="rma" id="rma" class="form-control" value="{{$data['data']->rma}}" required>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Description</small>
                        <input type="text" class="form-control" name="description" value="{{$data['data']->description}}" readonly>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Serial Number</small>
                        <input type="text" class="form-control" name="serialNumber" value="{{$data['data']->serialNumber}}" required>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Part Number</small>
                        <input type="text" class="form-control" name="partNumber" value="{{$data['data']->partNumber}}" readonly>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <small class="font-weight-bold">System Type</small>
                        <select name="systemType" id="" class="form-control">
                            <option value="">-</option>
                            @foreach ($data['systemTypes'] as $systemType)
                                <option value="{{$systemType['id']}}"
                                        @if($data['data']->systemType == $systemType['id'])
                                        selected
                                    @endif
                                >{{$systemType['systemType']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Operating System</small>
                        <input type="text" class="form-control" name="operatingSystem" value="{{$data['data']->operatingSystem}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Slot</small>
                        <input type="text" class="form-control" name="slot" value="{{$data['data']->slot}}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <small class="font-weight-bold">Stored In</small>
                        <select name="storedIn" id="" class="form-control">
                            <option value="">-</option>
                            @foreach ($data['locations'] as $location)
                                <option value="{{$location['id']}}" @if($location['id'] == $data['data']->storedIn) selected @endif>{{$location['location']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Location</small>
                        <input type="text" class="form-control" name="location" value="{{$data['data']->location}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Fault Code</small>
                        <select name="faultCode" id="" class="form-control">
                            <option value="">-</option>
                            @foreach ($data['faultCodes'] as $faultCode)
                                <option value="{{$faultCode['id']}}"
                                        @if($data['data']->faultCode == $faultCode['id'])
                                        selected
                                    @endif
                                >
                                    {{$faultCode['code']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Fault Details</small>
                        <textarea name="faultDetails" id="" cols="" rows="1" class="form-control">{{$data['data']->faultDetails}}</textarea>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <small class="font-weight-bold">Date Received</small>
                        <input type="date" name="dateReceived" class="form-control" value="{{$data['data']->dateReceived}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Incoming Tracking #</small>
                        <input type="text" class="form-control" name="incomingTrackingNumber" value="{{$data['data']->incomingTrackingNumber}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Received By</small>
                        <input type="text" name="receivedBy" class="form-control" value="{{$data['data']->receivedBy}}">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-3">
                        <small class="font-weight-bold">Cause of Fault</small>
                        <input type="text" class="form-control" name="causeOfFC" value="{{$data['data']->causeOfFC}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Cause of Fault Details</small>
                        <textarea name="causeOfFCDetails" id="" cols="" rows="1" class="form-control">{{$data['data']->causeOfFCDetails}}</textarea>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Start of Repair</small>
                        <input type="date" name="startOfRepair" id="" class="form-control" value="{{$data['data']->startOfRepair}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">End of Repair</small>
                        <input type="date" class="form-control" name="endOfRepair" value="{{$data['data']->endOfRepair}}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <small class="font-weight-bold">Turn Around Time</small>
                        <input type="text" name="turnAroundTime" class="form-control" value="{{$data['data']->turnAroundTime}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Repaired By</small>
                        <select name="fe" id="" class="form-control">
                            <option value="">-</option>
                            <option>-</option>
                            @foreach($data['fse'] as $fe)
                                <option value="{{$fe['id']}}"
                                        @if($data['data']->fe == $fe['id'])
                                        selected
                                    @endif
                                >{{$fe['lastname']}}, {{$fe['firstname']}} {{$fe['middlename'][0]}}.</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Type of Service</small>
                        <select name="typeOfService" id="" class="form-control">
                            <option value="">-</option>
                            @foreach($data['typeOfServices'] as $typeOfService)
                                <option value="{{$typeOfService['id']}}"
                                        @if($data['data']->typeOfService == $typeOfService['id'])
                                        selected
                                    @endif
                                >{{$typeOfService['typeOfService']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <small class="font-weight-bold">Findings</small>
                        <textarea name="findings" id="" cols="1" rows="2" class="form-control">{{$data['data']->findings}}</textarea>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">EWS Findings</small>
                        <textarea name="EWSFindings" id="" cols="1" rows="2" class="form-control">{{$data['data']->EWSFindings}}</textarea>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Bench Test Findings</small>
                        <textarea name="benchTestFindings" id="" cols="1" rows="2" class="form-control">{{$data['data']->benchTestFindings}}</textarea>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Work Performed</small>
                        <textarea name="workPerformed" id="" cols="1" rows="2" class="form-control">{{$data['data']->workPerformed}}</textarea>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <small class="font-weight-bold">Test Time (Hours)</small>
                        <input type="number" class="form-control" step=".01" name="testTime" value="{{$data['data']->testTime}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Repair Time (Hours)</small>
                        <input type="number" class="form-control" step=".01" name="repairTime" value="{{$data['data']->repairTime}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Upgrade Time (Hours)</small>
                        <input type="number" class="form-control" step=".01" name="upgradeTime" value="{{$data['data']->upgradeTime}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Status</small>
                        <select name="status" id="" class="form-control">
                            <option value="">-</option>
                            @foreach($data['status'] as $status)
                                <option value="{{$status->id}}"
                                        @if($data['data']->status == $status['id'])
                                        selected
                                    @endif
                                >{{$status->status}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <small class="font-weight-bold">Reason for Return</small>
                        <textarea name="reasonForReturn" id="" cols="1" rows="1" class="form-control">{{$data['data']->reasonForReturn}}</textarea>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Remarks</small>
                        <textarea name="remarks" id="" cols="1" rows="1" class="form-control">{{$data['data']->remarks}}</textarea>
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Ship To Customer Name</small>
                        <input type="text" class="form-control" name="shipToCustomerName" value="{{$data['data']->shipToCustomerName}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Address</small>
                        <textarea name="address" id="" cols="1" rows="1" class="form-control" value="{{$data['data']->address}}"></textarea>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <small class="font-weight-bold">Outgoing Tracking #</small>
                        <input type="text" class="form-control" name="outgoingTrackingNumber" value="{{$data['data']->outgoingTrackingNumber}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Date Shipped</small>
                        <input type="date" class="form-control" name="dateShipped" value="{{$data['data']->dateShipped}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Contact Person</small>
                        <input type="text" class="form-control" name="contactPerson" value="{{$data['data']->contactPerson}}">
                    </div>
                    <div class="col-3">
                        <small class="font-weight-bold">Contact #</small>
                        <input type="text" class="form-control" name="contactNumber" value="{{$data['data']->contactNumber}}">
                    </div>
                </div>
                <button class="btn btn-success float-right mt-3">Update RMA</button>
            </form>
        </div>
    </div>

    <div class="mt-2 card">
        <div class="card-body">
            <h5>Replacement Part(s)</h5>
            <span class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Create Replacement Part(s)</span>
            <div class="mt-2" style="max-height: 700px !important; overflow-y: auto;">
                @isset($data['replacements'])
                    @foreach($data['replacements'] as $replacement)
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action mb-1">
                                <div class="row">
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-3">
                                                <small class="font-weight-bold">Replacement Part</small>
                                                <div><strong>{{$replacement->replacement}}</strong> - {{$replacement->replacementPartNumber}}</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="font-weight-bold">Quantity</small>
                                                <div>{{$replacement->quantity}}</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="font-weight-bold">Reference Designator</small>
                                                <div>{{$replacement->reference_designator}}</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="font-weight-bold">Transaction Date</small>
                                                <div>{{$replacement->date_received_released}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2 ext-right my-3">
                                        <a href="{{route('movement.component.show', $replacement->id)}}"
                                           class="">View</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    @endforeach
                @endisset
            </div>
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
                <form action="{{route('board.replacement.create', $data['data']->id)}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="motherRecord" value="{{$data['data']->motherRecord}}">
                        <div class="row p-3">
                            <div class="col-3">
                                <label for="" class="font-weight-bold">Replacement Part(s)</label>
                            </div>
                            <div class="col-9">
                                <select name="reference" class="form-control" required>
                                    <option value="">-</option>
                                    @foreach($data['components'] as $replacement)
                                        <option value="{{$replacement->id}}">{{$replacement->description}} - {{$replacement->partNumber}} ({{$replacement->actualQuantity}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-3">
                                <label for="" class="font-weight-bold">Quantity to be used</label>
                            </div>
                            <div class="col-9">
                                <input type="number" name="quantity" class="form-control" required>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-3">
                                <label for="" class="font-weight-bold">Reference Designator</label>
                            </div>
                            <div class="col-9">
                                <input type="text" name="referenceDesignator" class="form-control" required>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-3">
                                <label for="" class="font-weight-bold">Date of Use</label>
                            </div>
                            <div class="col-9">
                                <input type="date" name="dateReceived" class="form-control" required>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-3">
                                <label for="" class="font-weight-bold">FSE</label>
                            </div>
                            <div class="col-9">
                                <input type="text" name="receivedBy" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="add-replacement" class="btn btn-primary">Add Part(s)</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
