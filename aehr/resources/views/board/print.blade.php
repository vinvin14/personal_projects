@extends('layouts.master')
@section('content')
    <script>
        window.onload = function() { window.print(); }
    </script>
    <style>
        .container {

        }
        * {
            font-size: 1.05rem;

        }
        table.table-bordered{
            border:1px solid black;
            margin-top:20px;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid black;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid black;
        }
    </style>
    {{--{{dd($data)}}--}}
    <div class="container mt-5">
        <h1 class="text-center">RMA Repair Summary Form</h1>
        <div class="row mt-5">
            <div class="col-6 border text-left">
                <h3 class="m-2">RMA Number: {{$data['rma']->rma ?? ''}}</h3>
            </div>
            <div class="col-6 border text-left">
                <h3 class="m-2">Organization: {{$data['rma']->name ?? ''}}</h3>
            </div>
        </div>
        <div class="my-3">
            <div class="row">
                <div class="col-6 text-left">
                    <h4>Part Number: <u>{{$data['rma']->partNumber ?? ''}}</u></h4>
                </div>
                <div class="col-6 text-left">
                    <h4>Description: <u>{{$data['rma']->description ?? ''}}</u></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-6 text-left">
                    <h4>Serial Number: <u>{{$data['rma']->serialNumber ?? ''}}</u></h4>
                </div>
                <div class="col-6 text-left">
                    <div>Upgrade to current revision?</div>
                    <span class="mr-5"> [ &nbsp; ] Yes </span><span class=""> [ &nbsp; ] No </span>
                </div>
            </div>
        </div>
        <div class="mt-2 font-italic font-weight-bold">
            Parts Replaced (on above item)
        </div>
        <div class="table-responsive mb-2">
            <table class="table table-bordered border">
                <thead>
                <th>Part Number</th>
                <th>Qty</th>
                <th>Part Description</th>
                <th>Location</th>
                <th>Failure Code</th>
                </thead>
                {{--<tbody>--}}
                {{--<tr>--}}
                    {{--<td>test</td>--}}
                    {{--<td>test</td>--}}
                    {{--<td>test</td>--}}
                    {{--<td>test</td>--}}
                    {{--<td>test</td>--}}
                {{--</tr>--}}
                <tbody>
                @foreach ($data['replacements'] as $replacement)
                    <tr>
                    <td>{{$replacement->partNumber}}</td>
                    <td>{{$replacement->quantity}}</td>
                    <td>{{$replacement->description}}</td>
                    <td>{{$replacement->reference_designator}}</td>
                    <td>{{$replacement->faultcode}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 border">
            <div class="m-2 font-weight-bold">Worked Performed:</div>
            <p class="m-2">{{$data['rma']->workPerformed ?? ''}}</p>
            {{--<p class="m-2">asdfasdfjalskdfalskdf;lasdjfsajdflakjsdflkjasdfljsalfkjas;ldfjasd--}}
                {{--alkdsjflasdjkfasdjf;asjdf--}}
                {{--adlkfjas;dlfjas;ldkjf;ldsakjfas--}}
                {{--aldskfja;dsljf;aldkjf;ladjf;lakjp;alkjdf;adhfahgdsfkgadfadslf</p>--}}
        </div>
        <div class="row mt-3">
            <div class="col-4">
                Tested at Bench [ &nbsp; ] Passed
            </div>
            <div class="col-4">
                Tested at Integration [ &nbsp; ] Passed
            </div>
            <div class="col-4">
                Calibration <small class="font-weight-bold font-italic">(if Required)</small> [ &nbsp; ] Calibrated
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-4">
                Tested at System [ &nbsp; ] Passed
            </div>
            <div class="col-4">
                Overnight Run [ &nbsp; ] Passed
            </div>
            <div class="col-4">
                Tested By: ______________________
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-6 text-right">
                Start Repair Date: <u>{{$data['rma']->startOfRepair}}</u>
                {{--Start Repair Date: <u>2021-01-12</u>--}}
            </div>
            <div class="col-6 text-left">
                End Repair Date: <u>{{$data['rma']->endOfRepair}}</u>
                {{--End Repair Date: <u>2021-01-12</u>--}}
            </div>
        </div>
        <div class="mt-4 border">
            <div class="m-2 font-weight-bold">Repair Times:</div>
            <div class="row m-2">
                <div class="col-4 text-left">
                    Upgrade Time: <u>{{$data['rma']->upgradeTime}} Hour(s)</u>
                    {{--Upgrade Time: <u>1</u> Hour(s)--}}
                </div>
                <div class="col-4 text-left">
                    Test Time: <u>{{$data['rma']->testTime}} Hour(s)</u>
                </div>
                <div class="col-4 text-left">
                    Repair Time: <u>{{$data['rma']->repairTime}} Hour(s)</u>
                </div>
            </div>
            <div class="row m-2">
                <div class="col-4 text-left">
                    Cal Time: ___ Hour(s
                </div>
                <div class="col-4 text-left">
                    Burn-In Time: ___ Hour(s)
                </div>
            </div>
        </div>
    </div>
@endsection
