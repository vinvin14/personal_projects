@extends('resources.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    {{--{{dd($data['consumable']->id)}}--}}
    <a href="{{route('tools')}}" class="btn btn-outline mt-1"><i class="fas fa-arrow-circle-left"></i> Back to Tools</a>
    <div class="card w-50 shadow-sm mb-3">
        <div class="card-body ">
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Woaah an Error!</strong>  {{ Session::get('error')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(Session::has('response'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Well done!</strong>  {{ Session::get('response')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <h5 class="border-bottom">Tool Information</h5>
            <form action="{{route('tool.upsave', $data['tools']->id)}}" method="post">
                @csrf
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Description
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="description" class="form-control" value="{{$data['tools']->description}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Part Number
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="partNumber" class="form-control" value="{{$data['tools']->partNumber}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Model Number
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="modelNumber" class="form-control" value="{{$data['tools']->modelNumber}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Brand
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="brand" class="form-control" value="{{$data['tools']->brand}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Vendor
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="vendor" class="form-control" value="{{$data['tools']->vendor}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Stored In
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <select name="storedIn" id="" class="form-control">
                                @foreach ($data['storedIn'] as $location)
                                    <option value="{{$location['id']}}"
                                            @if($data['tools']->storedInID == $location['id'])
                                            selected
                                        @endif
                                    >{{$location['location']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Location
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="location" id="" class="form-control" value="{{$data['tools']->location}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Quantity
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="number" name="actualQuantity" id="actualQuantity" class="form-control" value="{{$data['tools']->actualQuantity}}" @if($data['has_movement']) readonly @endif readonly>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Unit
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <select name="unit" id="" class="form-control">
                                @foreach ($data['units'] as $unit)
                                    <option value="{{$unit['id']}}"
                                            @if($data['tools']->unitID == $unit['id'])
                                            selected
                                        @endif
                                    >{{$unit['unit']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Unit Price
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="number" name="unitPrice" id="unitPrice" step=".01" class="form-control" value="{{$data['tools']->unitPrice}}">
                        </div>
                    </div>
                </div>
                {{--<div class="row py-2">--}}
                    {{--<div class="col-4 text-right">--}}
                        {{--<div class="font-weight-bold">--}}
                            {{--Total Price--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-8 text-left">--}}
                        {{--<div class="text-info">--}}
                            {{--<input type="number" name="totalPrice" id="totalPrice" step=".01" class="form-control" value="{{$data['tools']->totalPrice}}" readonly>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Depreciation Value
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="number" name="depreciationValue" step=".01" class="form-control" value="{{$data['tools']->depreciationValue}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Useful Life
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="usefulLife" class="form-control" value="{{$data['tools']->usefulLife}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Date Received
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="text" name="dateReceived" class="form-control" value="{{$data['tools']->dateReceived}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Calibration Date
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <input type="date" name="calibrationDate" class="form-control" value="{{$data['tools']->calibrationDate}}">
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Calibration Requirements
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <textarea name="calibrationReq" class="form-control" id="" cols="30" rows="10">{{$data['tools']->calibrationReq}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-4 text-right">
                        <div class="font-weight-bold">
                            Remarks
                        </div>
                    </div>
                    <div class="col-8 text-left">
                        <div class="text-info">
                            <textarea name="remarks" id="" cols="30" rows="10" class="form-control">{{$data['tools']->remarks}}</textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success float-right mt-1">Save Information</button>
            </form>
        </div>
    </div>
    <script>
        //setting of quantity rules
        var orMax = parseInt($('#maximumQuantity').val());
        var orMin = parseInt($('#minimumQuantity').val());
        var orActual = parseInt($('#actualQuantity').val());

        $('#maximumQuantity, #actualQuantity, #minimumQuantity, #unitPrice').on('blur', function (){
            var maximum = parseInt($('#maximumQuantity').val());
            var minimum = parseInt($('#minimumQuantity').val());
            var actual = parseInt($('#actualQuantity').val());
            var price = parseFloat($('#unitPrice').val());

            if(maximum === 0)
            {
                Swal.fire(
                    'Seriously?',
                    'Maximum Quantity should have a value! Reverting back to original amount',
                    'warning'
                )
                $('#maximumQuantity').val(orMax);
            }
            else if(minimum === 0)
            {
                Swal.fire(
                    'Seriously?',
                    'Minimum Quantity should have a value! Reverting back to original amount',
                    'warning'
                )
                $('#minimumQuantity').val(orMin);
            }
            else if(actual === 0)
            {
                Swal.fire(
                    'Seriously?',
                    'Actual Quantity should have a value! Reverting back to original amount',
                    'warning'
                )
                $('#actualQuantity').val(orActual);
            }
            else
            {
                if(maximum < actual)
                {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: true
                    });
                    swalWithBootstrapButtons.fire({
                        title: 'Hold on a sec!',
                        text: "Warning! Actual Quantity must be lesser than the Maximum Quantity, if you want to proceed with this changes Maximum will be the same as Actual Quantity",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, I would like to proceed! ',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            swalWithBootstrapButtons.fire(
                                'Success!',
                                'Maximum Quantity is the same as the Actual Quantity',
                                'success'
                            )
                            $('#maximumQuantity').val(actual);
                        } else if (
                            /* Read more about handling dismissals below */
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            swalWithBootstrapButtons.fire(
                                'Cancelled',
                                'Quantity will now revert back to its original amount',
                                'error'
                            )
                            $('#actualQuantity').val(orActual);
                        }
                    });
                }
                else if(minimum > actual)
                {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: true
                    });
                    swalWithBootstrapButtons.fire({
                        title: 'Hold on a sec!',
                        text: "Warning! Actual Quantity must be greater than the Minimum Quantity, if you want to proceed with this changes Minimum will be the same as Actual Quantity",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, I would like to proceed! ',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            swalWithBootstrapButtons.fire(
                                'Success!',
                                'Maximum Quantity is the same as the Actual Quantity',
                                'success'
                            )
                            $('#minimumQuantity').val(actual);
                        } else if (
                            /* Read more about handling dismissals below */
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            swalWithBootstrapButtons.fire(
                                'Cancelled',
                                'Quantity will now revert back to its original amount',
                                'error'
                            )
                            $('#minimumQuantity').val(orMin);
                        }
                    });
                }
            }
            $('#totalPrice').val((price*actual));
        });
    </script>
@endsection
