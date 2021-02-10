@extends('references.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    {{--{{dd($units)}}--}}
    <a href="{{route('reference.customers')}}" class="btn btn-outline my-1"><i class="fas fa-arrow-circle-left"></i> Back to Customers</a>
    <div class="row">
        <div class="col-6">
            <form action="{{route('reference.customer.store')}}" method="post">
                @csrf
                <div class="card">
                    <div class="card-body shadow-sm">
                        <h5 class="border-bottom">Add New Customer</h5>
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
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Customer ID</label>
                            <input type="text" name="customerID" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Customer Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="">Address</label>
                            <textarea type="text" name="address" class="form-control"></textarea>
                            <small class="font-italic font-weight-bold">Address Format (Street #/Bldg. #, Street Name, Municipality, Province)</small>
                        </div>
                        <button type="submit" class="btn btn-success form-control">Add New Custormer Record</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body shadow-sm">
                    <h5 class="border-bottom">Existing Customer(s)</h5>
                    @foreach($existing_customer as $customer)
                        <div class="list-group mb-1">
                            <a href="{{route('reference.customer.update', $customer->id)}}" class="list-group-item list-group-item-action">
                                <strong>{{$customer->customerID}}</strong> | <strong>{{$customer->name}}</strong>
                            </a>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
