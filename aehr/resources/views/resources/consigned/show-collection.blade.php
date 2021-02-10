@extends('resources.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('consigned')}}" class="btn btn-outline mt-1"><i class="fas fa-arrow-circle-left"></i> Back to
        Consigned Spares</a>
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="border-bottom">Consigned Spare list under <strong>Part Number: {{$data['partNumber']}} ({{count($data['data'])}})</strong></h6>
            <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
                <table class="table table-hover mt-2 border shadow-sm" id="reference-unit-table">
                    <thead class="thead-dark ">
                    <th>Part Number</th>
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
                        @foreach (@$data['data'] as $consigned)
                            <tr>
                            <td>{{$consigned->partNumber}}</td>
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
        </div>
    </div>
@endsection
