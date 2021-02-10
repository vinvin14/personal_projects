@extends('layouts.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content2')
    <a href="{{route('repair.show', $data['data']->motherRecordID)}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Mother Repair Record</a>
    <div class="">
        <div class="row">
            <div class="col-6">
                <div class="card shadow-sm ">
                    <div class="card-body " style="max-height: 800px; overflow-y: auto">
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
                        <h5 class="border-bottom">Board Details</h5>
                        <div class="" >
                            <div class="row">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        RMA
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->rma}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
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
                                        Serial Number
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->serialNumber}}
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
                                        {{$data['data']->partNumber}}
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
                                        {{$data['data']->location}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Type of Service
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->typeOfService}}
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
                                        {{$data['data']->systemType}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Operating System
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->operatingSystem}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Fault Code
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->faultCode}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Fault Code Details
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->faultDetails}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Cause of Fault Code
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->causeOfFC}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Start of Repair
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->startOfRepair}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        End of Repair
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->endOfRepair}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Field Service Engineer
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->lastname}}, {{$data['data']->firstname}} {{$data['data']->middlename[0]}}.
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
                                        {{$data['data']->location}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Slot
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->slot}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Status
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->status}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Job Status
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->jobStatus}}
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
                                        {{$data['data']->releasedBy}}
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Upgrade Time
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->upgradeTime}} Hour(s)
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Test Time
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->testTime}} Hour(s)
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Repair Time
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                        {{$data['data']->repairTime}} Hour(s)
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Work Performed
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                            <textarea name="" id="" cols="2" rows="3"
                                                      class="form-control"
                                                      readonly>{{$data['data']->workPerformed}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Findings
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                            <textarea name="" id="" cols="2" rows="3"
                                                      class="form-control"
                                                      readonly>{{$data['data']->findings}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        EWS Findings
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                            <textarea name="" id="" cols="2" rows="3"
                                                      class="form-control"
                                                      readonly>{{$data['data']->EWSFindings}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Bench Test Findings
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                            <textarea name="" id="" cols="2" rows="3"
                                                      class="form-control"
                                                      readonly>{{$data['data']->benchTestFindings}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-4 text-right">
                                    <div class="font-weight-bold">
                                        Reason for Return
                                    </div>
                                </div>
                                <div class="col-8 text-left">
                                    <div class="text-info">
                                            <textarea name="" id="" cols="2" rows="3"
                                                      class="form-control"
                                                      readonly>{{$data['data']->reasonForReturn}}</textarea>
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
                                <textarea name="" id="" cols="2" rows="3" class="form-control"
                                          readonly>{{$data['data']->remarks}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <a href="{{route('board.update', [$data['data']->motherRecord, $data['data']->id])}}"
                               class="btn btn-success float-right mt-1">Update Information</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="border-bottom">
                            Replacement Part(s)
                        </h5>
                        <span class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Create Replacement Part(s)</span>
                        <div class="mt-2" style="max-height: 700px !important; overflow-y: auto;">
                            @isset($data['replacements'])
                                @foreach($data['replacements'] as $replacement)
                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item-action mb-1">
                                            <div class="row">
                                                <div class="col-10">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <small class="font-weight-bold">Replacement Part</small>
                                                            <div><strong>{{$replacement->replacement}}</strong> - {{$replacement->replacementPartNumber}}</div>
                                                        </div>
                                                        <div class="col-4">
                                                            <small class="font-weight-bold">Quantity</small>
                                                            <div>{{$replacement->quantity}}</div>
                                                        </div>
                                                        <div class="col-4">
                                                            <small class="font-weight-bold">Transaction Date</small>
                                                            <div>{{$replacement->dateReceived}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2 ext-right my-3">
                                                    <a href="{{route('movements.show', ['components', $replacement->id])}}"
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
                    <input type="hidden" name="motherRecord" value="{{$data['data']->motherRecordID}}">
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
                            <label for="" class="font-weight-bold">Date of Use</label>
                        </div>
                        <div class="col-9">
                            <input type="date" name="dateReceived" class="form-control" required>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Dispensed By</label>
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
