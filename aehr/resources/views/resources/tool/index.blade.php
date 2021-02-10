@extends('resources.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('tool.create')}}" class="btn btn-primary mt-3">Add new Tool</a>
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
    <form action="{{route('tool.search')}}" method="get">
        <div class="input-group mt-2">
            <div class="input-group-prepend">
                <div class="">
                    <select name="search_by" id="" class="form-control bg-info text-light" required>
                        <option value="">Search By</option>
                        <option value="storedIn">Stored In</option>
                        <option value="partnumber_description">Description | Part #</option>
                    </select>
                </div>
            </div>
            <input type="text" name="keyword" class="form-control" id="inlineFormInputGroupUsername" placeholder="Search Keyword . ." required>
            <div class="input-group-prepend">
                <button type="submit" class="btn btn-outline-success">Search</button>
            </div>
        </div>
    </form>
    <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
        <table class="table table-hover mt-2 border shadow-sm" id="reference-unit-table">
            <thead class="thead-dark ">
            <th>Description</th>
            <th>Part Number</th>
            {{--<th>Model Number</th>--}}
            <th>Brand</th>
            <th>Quantity</th>
            <th>Unit</th>
            {{--<th>Unit Price</th>--}}
            <th>Total Price</th>
            {{--<th>Depreciation Value</th>--}}
            {{--<th>Useful life</th>--}}
            <th></th>
            </thead>
            <tbody>
            @isset($data)
                @foreach (@$data as $tool)
                    <tr>
                        <td>{{$tool->description}}</td>
                        <td>{{$tool->partNumber}}</td>
                        {{--<td>{{$tool->modelNumber}}</td>--}}
                        <td>{{$tool->brand}}</td>
                        <td>{{$tool->totalQuantity}}</td>
                        <td>{{$tool->unitName}}</td>
                        {{--<td>${{number_format($tool->unitPrice, 2)}}</td>--}}
                        <td>${{number_format($tool->totalPrices, 2)}}</td>
                        {{--<td>{{number_format($tool->depreciationValue, 2)}}</td>--}}
                        {{--<td>{{$tool->usefulLife}}</td>--}}
                        <td>
                            <div class="float-right">
                                <div class="dropdown">
                                    <small class="dropdown-toggle" type="button" id="dropdownMenu2"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </small>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <a class="dropdown-item" href="{{route('tool.collection.show', $tool->partNumber)}}"
                                           type="button"><i class="fas fa-eye text-primary"></i> View</a>
                                        {{--<a class="dropdown-item"--}}
                                           {{--href="{{route('tool.update' , $tool->id)}}" type="button"><i--}}
                                                {{--class="fas fa-edit "></i> Edit</a>--}}
                                        {{--<a class="dropdown-item" onclick="return confirm('Are you sure you want to delete?')"--}}
                                           {{--href="{{route('tool.destroy' , $tool->id)}}" type="button"><i--}}
                                                {{--class="fas fa-trash-alt text-danger"></i> Delete</a>--}}
                                    </div>
                                    {{--<div class="dropdown-menu" aria-labelledby="dropdownMenu2">--}}
                                        {{--<a class="dropdown-item" href="{{route('tool.show', $tool->id)}}"--}}
                                           {{--type="button"><i class="fas fa-eye text-primary"></i> View</a>--}}
                                        {{--<a class="dropdown-item"--}}
                                           {{--href="{{route('tool.update' , $tool->id)}}" type="button"><i--}}
                                                {{--class="fas fa-edit "></i> Edit</a>--}}
                                        {{--<a class="dropdown-item" onclick="return confirm('Are you sure you want to delete?')"--}}
                                           {{--href="{{route('tool.destroy' , $tool->id)}}" type="button"><i--}}
                                                {{--class="fas fa-trash-alt text-danger"></i> Delete</a>--}}
                                    {{--</div>--}}
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
@endsection
