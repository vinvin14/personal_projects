@extends('references.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('reference.fses')}}" class="btn mt-3"><i class="fas fa-arrow-circle-left"></i> Back to FSE List</a>
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
    <h5 class="border-bottom">FSE Search Result(s)</h5>
    <small class="font-weight-bold font-italic text-info">You have <strong>{{$total_results}}</strong> total result(s)</small>
    <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
        <table class="table table-hover mt-2 border shadow-sm" id="reference-unit-table">
            <thead class="thead-dark ">
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Position</th>
            <th></th>
            </thead>
            <tbody id="reference-unit">
            @foreach ($fse as $fse)
                <tr>
                    <td>{{$fse->lastname}}</td>
                    <td>{{$fse->firstname}}</td>
                    <td>{{$fse->middlename}}</td>
                    <td>{{$fse->position}}</td>
                    <td>
                        <div class="float-right">
                            <a href="{{route('reference.fse.update', $fse->id)}}"><i class="fas fa-eye pr-5"></i></a>
                            <a href="{{route('reference.fse.destroy', $fse->id)}}" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fas fa-trash-alt text-danger"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-2">
        </div>
    </div>
@endsection
