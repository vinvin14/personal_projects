@extends('references.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('reference.typeofservices')}}" class="btn btn mt-3"><i class="fas fa-arrow-circle-left"></i> Back to Type of Services</a>
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
    <h5 class="border-bottom">Search Result(s) for Type of Service</h5>
    <small class="font-weight-bold font-italic text-info">You have <strong>{{$total_results}}</strong> total result(s)</small>
    <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
        <table class="table table-hover mt-2 border shadow-sm">
            <thead class="thead-dark ">
            <th>Type of Service</th>
            <th>Description</th>
            <th></th>
            </thead>
            <tbody>
            @foreach ($type_of_services as $type_of_service)
                <tr>
                    <td>{{$type_of_service->typeOfService}}</td>
                    <td><textarea name="" class="form-control" cols="1" rows="1" readonly>{{$type_of_service->description}}</textarea></td>
                    <td>
                        <div class="float-right">
                            <a href="{{route('reference.typeofservice.update', $type_of_service->id)}}"><i class="fas fa-eye pr-5"></i></a>
                            <a href="{{route('reference.typeofservice.destroy', $type_of_service->id)}}" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fas fa-trash-alt text-danger"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-2">
            {{$type_of_services->render()}}
        </div>
    </div>
@endsection
