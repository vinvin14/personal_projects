@extends('references.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('reference.faultcode.create')}}" class="btn btn-primary shadow mt-3">Add Fault Code</a>
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
    <form action="{{route('reference.faultcode.search')}}" method="get">
        <div class="input-group mt-2">
            <input type="text" name="keyword" class="form-control" id="inlineFormInputGroupUsername" placeholder="Search Keyword . .">
            <div class="input-group-prepend">
                <button type="submit" class="btn btn-outline-success">Search</button>
            </div>
        </div>
    </form>
    <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
        <table class="table table-hover mt-2 border shadow-sm mt-2" id="reference-fc-table">
            <thead class="thead-dark ">
            <th>Code</th>
            <th>Type</th>
            <th>Description</th>
            <th></th>
            </thead>
            <tbody>
            @foreach ($faultcodes as $faultcode)
                <tr>
                    <td>{{$faultcode->code}}</td>
                    <td>{{$faultcode->type}}</td>
                    <td><textarea name="" class="form-control" cols="1" rows="1" readonly>{{$faultcode->description}}</textarea></td>
                    <td>
                        <div class="float-right">
                            <a href="{{route('reference.faultcode.update', $faultcode->id)}}"><i class="fas fa-eye pr-5"></i></a>
                            <a href="{{route('reference.faultcode.destroy', $faultcode->id)}}" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fas fa-trash-alt text-danger"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-2">
            {{$faultcodes->render()}}
        </div>
    </div>
@endsection
