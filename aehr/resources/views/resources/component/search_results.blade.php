@extends('resources.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('components')}}" class="btn btn-outline mt-1"><i class="fas fa-arrow-circle-left"></i> Back to Components</a>
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
            <strong>Congratulations!</strong> {{ Session::get('response')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <h3>Search Result(s) for Components</h3>
    <small class="text-info font-italic font-weight-bold">You have <strong>{{$total_results}}</strong> total result(s)</small>
    <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
        <table class="table table-hover mt-2 border shadow-sm" id="reference-unit-table">
            <thead class="thead-dark ">
            <th>Description</th>
            <th>Part Number</th>
            <th>Stored In</th>
            <th>Location</th>
            {{--<th>System Type</th>--}}
            <th>Quantity</th>
            <th>Unit</th>
            <th>Unit Price</th>
            <th>Total Price</th>
            <th></th>
            </thead>
            <tbody>
            @isset($data)
                @foreach (@$data as $component)
                    <tr>
                        <td>{{$component->description}}</td>
                        <td>{{$component->partNumber}}</td>
                        <td>{{$component->storedIn_name}}</td>
                        <td>{{$component->location}}</td>
                        {{--<td>{{$component->systemType}}</td>--}}
                        <td>
                            <span class="
                                @if($component->actualQuantity <= $component->minimumQuantity)
                                text-danger
@endif
                                ">
                                {{number_format($component->actualQuantity)}}
                            </span>
                        </td>
                        <td>{{$component->unit}}</td>
                        <td>${{number_format($component->unitPrice, 2)}}</td>
                        <td>${{number_format($component->totalPrice, 2)}}</td>
                        <td>
                            <div class="float-right">
                                <div class="dropdown">
                                    <small class="dropdown-toggle" type="button" id="dropdownMenu2"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </small>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <a class="dropdown-item" href="{{route('component.show', $component->id)}}"
                                           type="button"><i class="fas fa-eye text-primary"></i> View</a>
                                        <a class="dropdown-item"
                                           href="{{route('component.update' , $component->id)}}"><i
                                                class="fas fa-edit "></i> Edit</a>
                                        <a class="dropdown-item" onclick="return confirm('Are you sure you want to delete?')"
                                           href="{{route('component.destroy' , $component->id)}}" type="button"><i
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
        <div class="p-2">
            {{$data->render()}}
        </div>
    </div>
@endsection
