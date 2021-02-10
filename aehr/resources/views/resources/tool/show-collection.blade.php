@extends('resources.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('tools')}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Tools</a>
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="border-bottom">Tools list under <strong>Part Number: {{$data['partNumber']}} ({{count($data['data'])}})</strong></h6>
            <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
                <table class="table table-hover mt-2 border shadow-sm" id="reference-unit-table">
                    <thead class="thead-dark ">
                    <th>Description</th>
                    {{--<th>Part Number</th>--}}
                    <th>Model Number</th>
                    <th>Vendor</th>
                    <th>Stored In</th>
                    <th>Location</th>
                    <th>Unit Price</th>
                    <th>Useful Life</th>
                    <th>Depreciation Value</th>
                    <th>Date Received</th>
                    <th></th>
                    </thead>
                    <tbody>
                    @isset($data)
                        @foreach (@$data['data'] as $tool)
                            <tr>
                                <td>{{$tool->description}}</td>
                                <td>{{$tool->modelNumber}}</td>
                                <td>{{$tool->vendor}}</td>
                                <td>{{$tool->storedIn}}</td>
                                <td>{{$tool->location}}</td>
                                <td>${{number_format($tool->unitPrice, 2)}}</td>
                                <td>{{$tool->usefulLife}}</td>
                                <td>{{$tool->depreciationValue}}</td>
                                <td>{{$tool->dateReceived}}</td>
                                <td>
                                    <div class="float-right">
                                        <div class="dropdown">
                                            <small class="dropdown-toggle" type="button" id="dropdownMenu2"
                                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            </small>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                <a class="dropdown-item" href="{{route('tool.show', $tool->id)}}?partnum={{$tool->partNumber}}"
                                                   type="button"><i class="fas fa-eye text-primary"></i> View</a>
                                                <a class="dropdown-item"
                                                   href="{{route('tool.update' , $tool->id)}}?partnum={{$tool->partNumber}}" type="button"><i
                                                        class="fas fa-edit "></i> Edit</a>
                                                <a class="dropdown-item" onclick="return confirm('Are you sure you want to delete?')"
                                                   href="{{route('tool.destroy' , $tool->id)}}?partnum={{$tool->partNumber}}" type="button"><i
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
        </div>
    </div>
@endsection
