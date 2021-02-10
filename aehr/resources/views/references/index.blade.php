@extends('layouts.main')
@section('scripts')
<script src="{{asset('js/api.js')}}"></script>
<script src="{{asset('js/utilities.js')}}"></script>
<script src="{{asset('js/sweetalert2.all.min.js')}}"></script>
@endsection
@section('content2')
    <style>
        .modal-lg {
            max-width: 50%;
        }
    </style>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="units-tab" data-toggle="tab" href="#units-tab-content" role="tab" aria-controls="home" aria-selected="true">Units</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="fc-tab" data-toggle="tab" href="#fc-tab-content" role="tab" aria-controls="profile" aria-selected="false">Fault Codes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="st-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">System Type</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="ts-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Type of Service</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        {{--unit tab--}}

        <div class="tab-pane fade show active" id="units-tab-content" role="tabpanel" aria-labelledby="units-tab">
            <button class="btn btn-primary mt-3" id="unitsAdd" data-toggle="modal" data-target="#referenceModal" data-backdrop="static" data-keyboard="false">Add new Unit</button>
            <input type="text" class="form-control my-2" id="unit-search" placeholder="Search here . . .">
            <div class="table-responsive" style="overflow-y: auto; height: 600px !important;">
                <table class="table table-borderless mt-2" id="reference-unit-table">
                    <thead class="thead-dark ">
                    <th>Unit</th>
                    <th>Description</th>
                    <th></th>
                    </thead>
                    <tbody id="reference-unit"></tbody>
                </table>
            </div>
        </div>
        {{--fc tab--}}
        <div class="tab-pane fade" id="fc-tab-content" role="tabpanel" aria-labelledby="fc-tab">
            <button class="btn btn-primary mt-3" id="fcAdd" data-toggle="modal" data-target="#referenceModal" data-backdrop="static" data-keyboard="false">Add new Fault Code</button>
            <input type="text" class="form-control my-2" id="unit-search" placeholder="Search here . . .">
            <div class="table-responsive" style="overflow-y: auto; height: 600px !important;">
                <table class="table table-borderless mt-2" id="reference-fc-table">
                    <thead class="thead-dark ">
                    <th>Fault Code</th>
                    <th>Fault Type</th>
                    <th>Description</th>
                    <th></th>
                    </thead>
                    <tbody id="reference-fc"></tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="st-tab">...</div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="ts-tab">...</div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="referenceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="row">
                        <div class="col-md-8 border-right" id="col-target1">
                            <div class="container" id="col-target1-content"></div>
                        </div>
                        <div class="col-md-4" id="col-target2">
                            <h5 id="col-target2-title"></h5>
                            <div class="p-1" id="col-target2-content" style="overflow-y: auto; height: 350px"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="referenceAddButton"></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal2 -->
    <div class="modal fade" id="referenceView" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleView"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalContentView">
                    <div class="row">
                        <div class="col-md-8 border-right" id="col-view-target1">
                            <div class="container" id="col-view-target1-content"></div>
                        </div>
                        <div class="col-md-4" id="col-view-target2">
                            <h5 id="col-view-target2-title"></h5>
                            <div class="p-1" id="col-view-target2-content" style="overflow-y: auto; height: 350px"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="referenceUpdateButton"></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function (){
            $('#fc-tab').click(function () {
                reference('fc');
                $('#fc-tab-content').show();
                $('#units-tab-content').hide();
            });
            $('#units-tab').click(function () {
                reference('unit');
                $('#fc-tab-content').hide();
                $('#units-tab-content').show();
            });
            //unit
            reference('unit');
            search('#unit-search', '#reference-unit-table tr');
            $('#unitsAdd').click(function () {
                $('#referenceAddButton').html('Add Unit');
                $('#modalTitle').html('Add New Unit');
                $('#col-target1-content').html('' +
                    '<div class="p-2">' +
                    '<label class="font-weight-bold">Unit</label>' +
                    '<input class="form-control" id="unit">' +
                    '</div>' +
                    '<div class="p-2">' +
                    '<label class="font-weight-bold">Description</label>' +
                    '<textarea class="form-control" rows="10" id="unitDescription"></textarea>');
                $('#col-target2-title').text('Existing Units');
                $('#col-target2-content').html('');
                getWithTarget('api/units', '#col-target2-content', 'unit');
                $('#referenceAddButton').click(function () {
                    if(dataRequired(['#unit']) === 0)
                    {
                        var data = {
                            'unit' : $('#unit').val(),
                            'description' : $('#unitDescription').val(),
                        };
                        post('api/unit/create', data);
                        $('input, select').css('border-color', '#cccccc');
                        getWithTarget('api/units', '#col-target2-content', 'unit');
                        reference('unit');
                    }
                });
            });
            // fault code
            reference('fc');
            search('#unit-search', '#reference-fc-table tr');
            $('#fcAdd').click(function () {
                $('#referenceAddButton').html('Add Fault Code');
                $('#modalTitle').html('Add New Fault Code');
                $('#col-target1-content').html('' +
                    '<div class="p-2">' +
                    '<label class="font-weight-bold">Fault Code</label>' +
                    '<input class="form-control" id="faultCode">' +
                    '</div>' +
                    '<div class="p-2">' +
                    '<label class="font-weight-bold">Fault Type</label>' +
                    '<input class="form-control" id="faultType">' +
                    '</div>' +
                    '<div class="p-2">' +
                    '<label class="font-weight-bold">Description</label>' +
                    '<textarea class="form-control" rows="10" id="fcDescription"></textarea>');
                $('#col-target2-title').text('Existing Fault Codes');
                $('#col-target2-content').html('');
                getWithTarget('api/faultcodes', '#col-target2-content', 'fc');
                $('#referenceAddButton').click(function () {
                    if(dataRequired(['#faultCode', '#faultType']) === 0)
                    {
                        var data = {
                            'code' : $('#faultCode').val(),
                            'type' : $('#faultType').val(),
                            'description' : $('#fcDescription').val(),
                        };
                        console.log(data);
                        post('api/faultcode/create', data);
                        $('input, select').css('border-color', '#cccccc');
                        getWithTarget('api/faultcodes', '#col-target2-content');
                        reference('fc');
                    }
                });
            });
        });
    </script>
@endsection
