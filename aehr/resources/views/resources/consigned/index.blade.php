@extends('resources.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('content3')
    <style>
        .modal-lg {
            max-width: 80%;
        }
    </style>
    <a href="{{route('consigned.create')}}" class="btn btn-primary mt-3">Add new Consigned Spares</a>
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
    {{--{{dd($data)}}--}}
    <form action="{{route('consigned.search')}}" method="get">
        <div class="input-group mt-2">
            <div class="input-group-prepend">
                <div class="">
                    <select name="search_by" id="" class="form-control bg-info text-light" required>
                        <option value="">Search By</option>
                        <option value="storedIn">Stored In</option>
                        <option value="partnumber_description">Description | Part #</option>
                    </select>
                </div>
            </div>
            <input type="text" name="keyword" class="form-control" id="inlineFormInputGroupUsername" placeholder="Search Keyword . .">
            <div class="input-group-prepend">
                <button type="submit" class="btn btn-outline-success">Search</button>
            </div>
        </div>
    </form>
    <div class="table-responsive" style="overflow-y: auto; height: 700px !important;">
        <table class="table table-hover mt-2 border shadow-sm" id="reference-unit-table">
            <thead class="thead-dark">
            <th>Part Number</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Total Price</th>
            <th></th>
            </thead>
            <tbody>
            @isset($data)
                @foreach (@$data as $consigned)
                    <tr>
                        <td>{{$consigned->partNumber}}</td>
                        <td>{{$consigned->description}}</td>
                        <td><a href="#" data-toggle="modal" id="view-trigger" data-partnum="{{$consigned->partNumber}}" data-target="#exampleModal">{{number_format($consigned->totalQuantity)}}</a></td>
                        <td>{{$consigned->unitName}}</td>
                        <td>${{number_format($consigned->totalPrices, 2)}}</td>
                        <td>
                            <div class="float-right">
                                <div class="dropdown">
                                    <small class="dropdown-toggle" type="button" id="dropdownMenu2"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </small>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <a class="dropdown-item" href="{{route('consigned.collection.show', $consigned->partNumber)}}"
                                           type="button"><i class="fas fa-eye text-primary"></i> View</a>
                                        {{--<a class="dropdown-item"--}}
                                           {{--href="{{route('consigned.update' , $consigned->partNumber)}}" type="button"><i--}}
                                                {{--class="fas fa-edit "></i> Edit</a>--}}
                                        {{--<a class="dropdown-item" onclick="return confirm('Are you sure you want to delete?')"--}}
                                           {{--href="{{route('consigned.destroy' , $consigned->partNumber)}}" type="button"><i--}}
                                                {{--class="fas fa-trash-alt text-danger"></i> Delete</a>--}}
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Consigned Spare List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="">
                                <th>Part Number</th>
                                <th>Serial Number</th>
                                <th>Vendor</th>
                                <th>System Type</th>
                                {{--<th>Board Type</th>--}}
                                <th>Stored In</th>
                                <th>Location</th>
                                <th>Date Received</th>
                                <th>Unit Price</th>
                                <th>Depreciation Value</th>
                            </thead>
                            <tbody id="consigned-target">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('a[id="view-trigger"]').click(function () {
            var partnum = $(this).attr('data-partnum');
            $.ajax({
                url: '/api/resources/consigned/'+partnum,
                type: 'get',
                success:function (data) {
                    console.log(data)
                    $('#consigned-target').html('');
                    data.forEach(function (val) {
                        $('#consigned-target').append('' +
                            '<tr>' +
                            '<td>' + val.partNumber + '</td>' +
                            '<td>' + val.serialNumber + '</td>' +
                            '<td>' + val.vendor + '</td>' +
                            '<td>' + val.systemType + '</td>' +
                            // '<td>' + val.boardType + '</td>' +
                            '<td>' + val.storedIn + '</td>' +
                            '<td>' + val.location + '</td>' +
                            '<td>' + val.dateReceived + '</td>' +
                            '<td>' + val.unitPrice + '</td>' +
                            '<td>' + val.depreciationValue + '</td>' +
                            '</tr>');
                    });
                }
            })
        });
    </script>
@endsection
