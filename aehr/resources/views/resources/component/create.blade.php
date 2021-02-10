@extends('resources.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <a href="{{route('components')}}" class="btn btn-outline mt-1"><i class="fas fa-arrow-circle-left"></i> Back to Components</a>
    <div class="row mb-3">
        <div class="col-6 border-right">
            <div class="card card-body shadow-sm">
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
                <h5 class="border-bottom">Create new Component</h5>
                <form action="{{route('component.store')}}" method="post">
                    @csrf
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Transaction</label>--}}
                        {{--<select name="transaction" id="transaction" class="form-control">--}}
                            {{--<option value="">-</option>--}}
                            {{--<option>Initial Balancing</option>--}}
                            {{--<option>Incoming</option>--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    {{--<div class="form-group" id="invoiceNumber" style="display: none">--}}
                        {{--<label class="font-weight-bold" for="">Invoice Number</label>--}}
                        {{--<input type="text" class="form-control" name="invoiceNumber">--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Description</label>
                        <input type="text" name="description" style="text-transform:capitalize"  class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Part Number</label>
                        <input type="text" name="partNumber" style="text-transform:uppercase" class="form-control" required>
                    </div>
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Reference Designator</label>--}}
                        {{--<input type="text" name="referenceDesignator" class="form-control" required>--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Vendor</label>
                        <input type="text" name="vendor" class="form-control" required>
                    </div>
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">System Type</label>--}}
                        {{--<select name="systemType" id="" class="form-control" required>--}}
                            {{--<option value="">-</option>--}}
                            {{--@foreach (@$data['systemTypes'] as $systemType)--}}
                                {{--<option value="{{$systemType['id']}}">{{@$systemType['systemType']}}</option>--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="font-weight-bold" for="">Board Type</label>--}}
                        {{--<select name="boardType" id="" class="form-control" required>--}}
                            {{--<option value="">-</option>--}}
                            {{--@foreach (@$data['boardTypes'] as $boardType)--}}
                                {{--<option value="{{$boardType['id']}}">{{@$boardType['boardType']}}</option>--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Stored In</label>
                        <select name="storedIn" id="" class="form-control" required>
                            <option value="">-</option>
                            @foreach (@$data['storedIn'] as $location)
                                <option value="{{$location['id']}}">{{@$location['location']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Location</label>
                        <input type="text" name="location" id="" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Maximum Quantity</label>
                        <input type="number" name="maximumQuantity" id="maximumQuantity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Actual Quantity</label>
                        <input type="number" name="actualQuantity" id="actualQuantity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Minimum Quantity</label>
                        <input type="number" name="minimumQuantity" id="minimumQuantity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Unit</label>
                        <select name="unit" id="" class="form-control" required>
                            <option value="">-</option>
                            @foreach (@$data['units'] as $unit)
                                <option value="{{$unit['id']}}">{{@$unit['unit']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Unit Price</label>
                        <input type="number" name="unitPrice" step=".01" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Date Received</label>
                        <input type="date" name="dateReceived" class="form-control" required>
                    </div>
                    {{--<div class="form-group" id="receivedBy" style="display: none">--}}
                        {{--<label class="font-weight-bold" for="">Received By</label>--}}
                        {{--<input type="text" name="receivedBy" class="form-control">--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="10"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success form-control">Save Component</button>
                </form>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-body shadow-sm">
                <div style="overflow-y: auto; max-height: 600px !important;">
                    <h5 class="border-bottom pl-2">Existing Component(s)</h5>
                    <div class="p-2">
                        @foreach (@$data['components'] as $component)
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-action mb-1">
                                    {{$component['description']}} - <strong>{{$component['partNumber']}}</strong>
                                    (<strong class="text-info
                                    @if($component['actualQuantity'] <= $component['minimumQuantity'])
                                        text-danger
                                    @else
                                        text-info
                                    @endif
                                        ">
                                        {{$component['actualQuantity']}}
                                    </strong>)
                                    <a href="{{route('component.show', $component['id'])}}" class="float-right">View</a>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        //setting of quantity rules
        var orMax = parseInt($('#maximumQuantity').val());
        var orMin = parseInt($('#minimumQuantity').val());
        var orActual = parseInt($('#actualQuantity').val());

        $('#maximumQuantity, #actualQuantity, #minimumQuantity').on('blur', function (){
            var maximum = parseInt($('#maximumQuantity').val());
            var minimum = parseInt($('#minimumQuantity').val());
            var actual = parseInt($('#actualQuantity').val());

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
                            $('#minimumQuantity').val(orActual);
                        }
                    });
                }
            }
        });
        $('#transaction').on('input', function (){
            if($(this).val() === 'Incoming')
            {
                $('#invoiceNumber').show();
                $('#receivedBy').show();
            }
            else
            {
                $('#invoiceNumber').hide();
                $('#receivedBy').hide();
            }
        });
    </script>
@endsection
