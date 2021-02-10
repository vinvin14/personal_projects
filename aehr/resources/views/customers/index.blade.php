@extends('layouts.main')
@section('content2')
<div class="row p-3">
    <div class="col-6">
       <div class="card card-body">
           <h5 class="border-bottom">Customer(s) with Transaction(s)</h5>

       </div>
    </div>
    <div class="col-6">
       <div class="card card-body">
           <h5 class="border-bottom">
               List of Customer(s)
           </h5>
           @foreach($data as $customer)
               <div class="list-group">
                   <a href="#" class="list-group-item list-group-item-action m-2">
                       <div class="row">
                           <div class="col-6">
                               <small class="font-weight-bold">Customer Name</small>
                               <div class="p1">{{$customer->name}}</div>
                           </div>
                           <div class="col-6">
                               <small class="font-weight-bold">Customer Address</small>
                               <div class="p1">{{$customer->address}}</div>
                           </div>
                       </div>
                   </a>
               </div>
           @endforeach
       </div>

    </div>
</div>
@endsection
