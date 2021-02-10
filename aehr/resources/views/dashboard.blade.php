@extends('layouts.main')
@section('title', 'Dashboard')
@section('content2')
    {{--{{dd($weekly_rma_released)}}--}}

    <h2 class="">Dashboard</h2>
    <div class="row mt-2">
        @if($notifications > 0)
        <div class="col-4">
            <div class="card card-body shadow-sm">
                <small class="font-weight-bold border-bottom"><i class="fas fa-bell"></i> System Notification(s)</small>
                    <div class="p-1 text-info mt-2">
                        <a href="{{route('notifications')}}"><strong>{{$notifications}}</strong> Notification(s) that requires your attention</a>
                    </div>
            </div>
        </div>
        @endif
        <div class="col-4"></div>
        <div class="col-4"></div>
    </div>
    <div class="row mt-3">
        {{--<div class="col-6">--}}
        {{--<div class="card">--}}
        {{--<div class="card-body">--}}
        {{--<h3 class="font-weight-bold">RMA Boards Breakdown </h3>--}}
        {{--<canvas class="my-4 w-100" id="myChart3" width="900" height="380"></canvas>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        <div class="col-6">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class=" border-bottom font-weight-bold">Board Repair Status (Active)</h3>
                    <canvas class="mt-4 w-100" id="myChart" width="900" height="390"></canvas>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="font-weight-bold border-bottom">Released RMA this week </h3>
                    <canvas class="my-3 w-100" id="myChart4" width="900" height="380"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 border-right">
            <div class="card shadow">
                <div class="card-body">
                    <div class="p-1">
                        <h3 class="font-weight-bold border-bottom">Total RMA </h3>
                        <h5 class="font-weight-bold">{{array_sum($count_open_rma)}} Total Open</h5>
                    </div>
                    <canvas class="my-4 w-100" id="myChart2" width="900" height="380"></canvas>
                </div>
            </div>
        </div>
        {{--<div class="col-6">--}}
            {{--<div class="card">--}}
                {{--<div class="card-body">--}}
                    {{--<h3 class="pb-4 font-weight-bold">Board Repair Status (Active)</h3>--}}
                    {{--<canvas class="my-4 w-100" id="myChart" width="900" height="445"></canvas>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
    {{--<div style="width:75%;">--}}
        {{--<canvas id="canvas"></canvas>--}}
    {{--</div>--}}
    <div class="mt-3 mb-5">
        <div class="card shadow">
            <div class="card-body" style="max-height: 500px; overflow-y: auto">
                <h3 class="font-weight-bold border-bottom">Board Types</h3>
                @foreach ($board_types as $board_type)
                    <div class="font-weight-bold">
                        {{$board_type->description}} ({{$board_type->total_rma}} Total RMA)
                    </div>
                    <div class="p-2 border-bottom">
                        <div class="progress">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                 style="width:
                                 @if($board_type->total_rma == max($count_board_type))
                                     100%
                                 @else
                                     {{($board_type->total_rma/ max($count_board_type) * 100)}}%
                                 @endif

                                     ">
                                <span class="font-weight-bold">{{$board_type->total_rma}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>--}}
    <script>window.jQuery || document.write('<script src="/docs/4.5/assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>--}}
    <script src="{{asset('chart/chart.js')}}"></script>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Unrepairable', 'Repaired', 'For Repair'],
                datasets: [{
                    label: '# of Votes',
                    data: [{{$unrepairable}}, {{$repaired}}, {{$for_repair}}],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        // 'rgba(75, 192, 192, 0.2)',
                        // 'rgba(153, 102, 255, 0.2)',
                        // 'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        // 'rgba(153, 102, 255, 1)',
                        // 'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            // options: {
            //     scales: {
            //         yAxes: [{
            //             ticks: {
            //                 beginAtZero: true
            //             }
            //         }]
            //     }
            // }
        });

        var ctx = document.getElementById('myChart2').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: [
                    @foreach ($open_rma as $customer)
                        [ "{{$customer->name}}"],
                    @endforeach
                ],
                datasets: [{
                    label: 'Open RMA this Month',
                    data: [
                        @foreach ($open_rma as $customer)
                            [ "{{$customer->board_count}}"],
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                        }
                    }]
                }
            }
        });
        {{--var ctx = document.getElementById('myChart3').getContext('2d');--}}
        {{--var myChart = new Chart(ctx, {--}}
            {{--type: 'bar',--}}
            {{--data: {--}}
                {{--labels: [--}}
                    {{--@foreach ($data['board_type'] as $board_type)--}}
                        {{--[ "{{$board_type->description}}"],--}}
                    {{--@endforeach--}}
                {{--],--}}
                {{--datasets: [{--}}
                    {{--label: 'RMA',--}}
                    {{--data: [--}}
                        {{--@foreach ($data['board_type'] as $board_type)--}}
                            {{--[ "{{$board_type->total_rma}}"],--}}
                        {{--@endforeach--}}
                    {{--],--}}
                    {{--backgroundColor: [--}}
                        {{--'rgba(255, 99, 132, 0.2)',--}}
                        {{--'rgba(54, 162, 235, 0.2)',--}}
                        {{--'rgba(255, 206, 86, 0.2)',--}}
                        {{--'rgba(75, 192, 192, 0.2)',--}}
                        {{--'rgba(153, 102, 255, 0.2)',--}}
                        {{--'rgba(255, 159, 64, 0.2)'--}}
                    {{--],--}}
                    {{--borderColor: [--}}
                        {{--'rgba(255, 99, 132, 1)',--}}
                        {{--'rgba(54, 162, 235, 1)',--}}
                        {{--'rgba(255, 206, 86, 1)',--}}
                        {{--'rgba(75, 192, 192, 1)',--}}
                        {{--'rgba(153, 102, 255, 1)',--}}
                        {{--'rgba(255, 159, 64, 1)'--}}
                    {{--],--}}
                    {{--borderWidth: 1--}}
                {{--}]--}}
            {{--},--}}
            {{--options: {--}}
                {{--legend: {--}}
                    {{--display: false--}}
                {{--},--}}
                {{--scales: {--}}
                    {{--yAxes: [{--}}
                        {{--ticks: {--}}
                            {{--beginAtZero: true,--}}
                            {{--stepSize: 1,--}}
                        {{--}--}}
                    {{--}]--}}
                {{--}--}}
            {{--}--}}
        {{--});--}}
        var ctx = document.getElementById('myChart4').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    {{--{{'Mon'.$data['mon_rma']['date']}}--}}
                    ["Mon {{$weekly_rma_released['mon_rma']['date']}}"],
                    ["Tue {{$weekly_rma_released['tue_rma']['date']}}"],
                    ["Wed {{$weekly_rma_released['wed_rma']['date']}}"],
                    ["Thu {{$weekly_rma_released['thu_rma']['date']}}"],
                    ["Fri {{$weekly_rma_released['fri_rma']['date']}}"],
                    ["Sat {{$weekly_rma_released['sat_rma']['date']}}"],
                    ["Sun {{$weekly_rma_released['sun_rma']['date']}}"],
                ],
                datasets: [{
                    label: 'RMA',
                    data: [
                        ["{{$weekly_rma_released['mon_rma']['mon_rma']}}"],
                        ["{{$weekly_rma_released['tue_rma']['tue_rma']}}"],
                        ["{{$weekly_rma_released['wed_rma']['wed_rma']}}"],
                        ["{{$weekly_rma_released['thu_rma']['thu_rma']}}"],
                        ["{{$weekly_rma_released['fri_rma']['fri_rma']}}"],
                        ["{{$weekly_rma_released['sat_rma']['sat_rma']}}"],
                        ["{{$weekly_rma_released['sun_rma']['sun_rma']}}"],
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                        }
                    }]
                }
            }
        });
    </script>
@endsection
