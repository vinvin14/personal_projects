@extends('references.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('reference.unit.create')}}" class="btn btn-primary mt-3 shadow">Add new Unit</a>
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
            <strong>Woaah an Error!</strong>  {{ Session::get('error')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(Session::has('response'))
        <div class="alert alert-success alert-dismissible fade show mt-1" role="alert">
            <strong>Well done!</strong>  {{ Session::get('response')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <form action="{{route('reference.unit.search')}}" method="get">
        <div class="input-group mt-2">
            <input type="text" name="keyword" class="form-control" id="inlineFormInputGroupUsername" placeholder="Search Keyword . .">
            <div class="input-group-prepend">
                <button type="submit" class="btn btn-outline-success">Search</button>
            </div>
        </div>
    </form>
    <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
        <table class="table table-hover mt-2 border shadow-sm" id="reference-unit-table">
            <thead class="thead-dark ">
            <th>Unit</th>
            <th>Description</th>
            <th></th>
            </thead>
            <tbody id="reference-unit">
            @foreach ($units as $unit)
                <tr>
                    <td>{{$unit['unit']}}</td>
                    <td><textarea name="" class="form-control" cols="1" rows="1" readonly>{{$unit['description']}}</textarea></td>
                    <td>
                        <div class="float-right">
                            <a href="{{route('reference.unit.update', $unit['id'])}}"><i class="fas fa-eye pr-5"></i></a>
                            <a href="{{route('reference.unit.destroy', $unit['id'])}}" onclick="return confirm('Are you sure you want to delete this unit record?')"><i class="fas fa-trash-alt text-danger"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-2">
            {{$units->render()}}
        </div>
    </div>

    {{--<!-- Modal -->--}}
    {{--<div class="modal fade" id="referenceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
        {{--<div class="modal-dialog modal-lg" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<h5 class="modal-title">Create New Unit</h5>--}}
                    {{--<button type="button" class="close" data-dismiss="modal" id="modal-add-x" aria-label="Close">--}}
                        {{--<span aria-hidden="true">&times;</span>--}}
                    {{--</button>--}}
                {{--</div>--}}
                {{--<div class="modal-body" id="modalContent">--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-md-8 border-right" id="col-target1">--}}
                            {{--<div class="container" id="col-target1-content">--}}
                                {{--<div class="p-2">--}}
                                    {{--<label for="" class="font-weight-bold">Unit</label>--}}
                                    {{--<input type="text" class="form-control" id="unit">--}}
                                {{--</div>--}}
                                {{--<div class="p-2">--}}
                                    {{--<label for="" class="font-weight-bold">Description</label>--}}
                                    {{--<textarea class="form-control" id="description" cols="3" rows="10"></textarea>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-4" id="col-target2">--}}
                            {{--<h5>Existing Units</h5>--}}
                            {{--<div class="p-1" id="col-target2-content" style="overflow-y: auto; height: 350px">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-secondary" id="modal-add-close" data-dismiss="modal">Close</button>--}}
                    {{--<button type="button" class="btn btn-primary" id="referenceAddButton">Create Unit</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<!-- Modal2 -->--}}
    {{--<div class="modal fade" id="referenceView" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
        {{--<div class="modal-dialog modal-lg" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<h5 class="modal-title">Update Existing Unit</h5>--}}
                    {{--<button type="button" class="close" data-dismiss="modal" id="modal-view-x" aria-label="Close">--}}
                        {{--<span aria-hidden="true">&times;</span>--}}
                    {{--</button>--}}
                {{--</div>--}}
                {{--<div class="modal-body" id="modalContentView">--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-md-8 border-right" id="col-update-target1">--}}
                            {{--<div class="container" id="col-update-target1-content"></div>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-4" id="col-view-target2">--}}
                            {{--<h5>Existing Unit(s)</h5>--}}
                            {{--<div class="p-1" id="col-update-target2-content" style="overflow-y: auto; height: 350px"></div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-secondary" id="modal-view-close" data-dismiss="modal">Close</button>--}}
                    {{--<button type="button" class="btn btn-primary" id="referenceUpdateButton">Update Unit</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<script>--}}
        {{--// $(document).ready(function (){--}}
        {{--//     reference('unit');--}}
        {{--//     getWithTarget('/api/units', '#col-target2-content', 'unit');--}}
        {{--//     $('#referenceAddButton').click(function () {--}}
        {{--//         if(dataRequired(['#unit']) === 0)--}}
        {{--//         {--}}
        {{--//             var data = {--}}
        {{--//                 'unit' : $('#unit').val(),--}}
        {{--//                 'description' : $('#description').val(),--}}
        {{--//             };--}}
        {{--//             post('/api/unit/create', data);--}}
        {{--//             $('input, select').css('border-color', '#cccccc');--}}
        {{--//             getWithTarget('/api/units', '#col-target2-content', 'unit');--}}
        {{--//             // reference('unit');--}}
        {{--//         }--}}
        {{--//     });--}}
        {{--//     $('#modal-add-close, #modal-add-x, #modal-view-close, #modal-view-x').click(function () {--}}
        {{--//         location.reload();--}}
        {{--//     });--}}
        {{--// });--}}
    {{--</script>--}}
@endsection
