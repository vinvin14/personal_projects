@extends('references.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('reference.fse.create')}}" class="btn btn-primary shadow mt-3">Add New FSE</a>
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
    <form action="{{route('reference.fse.search')}}" method="get">
        <div class="input-group mt-2">
            <input type="text" name="keyword" class="form-control" id="inlineFormInputGroupUsername" placeholder="Search Keyword . .">
            <div class="input-group-prepend">
                <button type="submit" class="btn btn-outline-success">Search</button>
            </div>
        </div>
    </form>
    <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
        <table class="table table-hover mt-2 border shadow-sm mt-2" id="reference-customer-table">
            <thead class="thead-dark ">
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Position</th>
            <th></th>
            </thead>
            <tbody>
            @foreach ($fse as $engineer)
                <tr>
                    <td>{{$engineer->lastname}}</td>
                    <td>{{$engineer->firstname}}</td>
                    <td>{{$engineer->middlename}}</td>
                    <td>{{$engineer->position}}</td>
                    <td>
                        <div class="float-right">
                            <a href="{{route('reference.fse.update', $engineer->id)}}"><i class="fas fa-eye pr-5"></i></a>
                            <a href="{{route('reference.fse.destroy', $engineer->id)}}" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fas fa-trash-alt text-danger"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-2">
            {{$fse->render()}}
        </div>
    </div>
@endsection
