@extends('layouts.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content2')
    <style>
        .modal-lg {
            max-width: 50%;
        }
    </style>
    <a href="{{route('repair.show', $data['motherRecord'])}}" class="btn btn-outline mt-1"><i
            class="fas fa-arrow-circle-left"></i> Back to Mother Repair Record</a>
    <div class="card mb-3">
        <div class="card-body shadow-sm">
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Woaah an Error!</strong> {{ Session::get('error')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(Session::has('response'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ Session::get('response')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <h5 class="border-bottom">Replacement Part Details</h5>
            <form action="{{route('board.replacement.parts.store', $data['motherRecord'])}}" method="post">
                @csrf

                <button class="btn btn-success">Record RMA</button>
            </form>
        </div>
    </div>
@endsection
