@extends('resources.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <div class="card my-3">
        <div class="card-body">
            <h6 class="border-bottom">Equipment list under <strong>Part Number: {{$data['partNumber']}} ({{count($data['data'])}})</strong></h6>
            <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
                <table class="table table-hover mt-2 border shadow-sm" id="reference-unit-table">
                    <thead class="thead-dark ">
                    <th>Description</th>
                    {{--<th>Part Number</th>--}}
                    <th>Serial Number</th>
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
                        @foreach (@$data['data'] as $equipment)
                            <tr>
                                <td>{{$equipment->description}}</td>
                                <td>{{$equipment->serialNumber}}</td>
                                <td>{{$equipment->modelNumber}}</td>
                                <td>{{$equipment->vendor}}</td>
                                <td>{{$equipment->storedIn}}</td>
                                <td>{{$equipment->location}}</td>
                                <td>${{number_format($equipment->unitPrice, 2)}}</td>
                                <td>{{$equipment->usefulLife}}</td>
                                <td>{{$equipment->depreciationValue}}</td>
                                <td>{{$equipment->dateReceived}}</td>
                                <td>
                                    <div class="float-right">
                                        <div class="dropdown">
                                            <small class="dropdown-toggle" type="button" id="dropdownMenu2"
                                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            </small>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                <a class="dropdown-item" href="{{route('equipment.show', $equipment->id)}}?partnum={{$equipment->partNumber}}"
                                                   type="button"><i class="fas fa-eye text-primary"></i> View</a>
                                                <a class="dropdown-item"
                                                   href="{{route('equipment.update' , $equipment->id)}}?partnum={{$equipment->partNumber}}" type="button"><i
                                                        class="fas fa-edit "></i> Edit</a>
                                                <a class="dropdown-item" onclick="return confirm('Are you sure you want to delete?')"
                                                   href="{{route('equipment.destroy' , $equipment->id)}}?partnum={{$equipment->partNumber}}" type="button"><i
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
