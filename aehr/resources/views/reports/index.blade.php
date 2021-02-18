@extends('layouts.main')
@section('scripts')
    <script src="{{asset('js/api.js')}}"></script>
    <script src="{{asset('js/utilities.js')}}"></script>
@endsection
@section('title', 'Reports')
@section('content2')
    <h2>AEHR Reports</h2>
    <nav class="nav border-bottom reference-nav">
        <a class="nav-link
            @if(request()->query('type') == 'repairs')
            reference-nav-active
            @endif
            " href="/reports?type=repairs">Repairs
        </a>
        <a class="nav-link
            @if(request()->query('type') == 'consumables')
            reference-nav-active
            @endif
            " href="/reports?type=consumables">Consumables
        </a>
        <a class="nav-link
            @if(request()->query('type') == 'components')
            reference-nav-active
            @endif
            " href="/reports?type=components">Components
        </a>
        <a class="nav-link
            @if(request()->query('type') == 'consigned')
            reference-nav-active
            @endif
            " href="/reports?type=consigned">Consigned Spares
        </a>
        <a class="nav-link
            @if(request()->query('type') == 'equipment')
            reference-nav-active
            @endif
            " href="/reports?type=equipment">Equipment
        </a>
        <a class="nav-link
            @if(request()->query('type') == 'tools')
            reference-nav-active
            @endif
            " href="/reports?type=tools">Tools
        </a>
    </nav>
    {{--<div class="list-group w-50 shadow-sm">--}}
        {{--<a href="{{route('reports.repairs')}}" target="_blank" class="list-group-item list-group-item-action">Sample Repair Reports</a>--}}
        {{--<a href="{{route('reports.equipment')}}" class="list-group-item list-group-item-action">Sample Equipment Reports</a>--}}
    {{--</div>--}}
    @if(request()->query('type') == 'repairs')
        <div class="form-group mt-3">
            <form action="{{route('reports.generate', 'repairs')}}" method="post">
                @csrf
                <h5 class="my-3">Generate Repairs Report</h5>
                <div class="row container">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">From</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="from" class="form-control" required>
                    </div>
                </div>
                <div class="row container mt-3">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">To</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="to" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3 shadow">Generate Report</button>
            </form>
        </div>
    @endif
    @if(request()->query('type') == 'consumables')
        <div class="form-group mt-3">
            <form action="{{route('reports.generate', 'consumables')}}" method="post">
                @csrf
                <h5 class="my-3">Generate Consumables Report</h5>
                <div class="row container">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">Type</label>
                    </div>
                    <div class="col-10">
                        <select name="type" id="" class="form-control" required>
                            <option value="">-</option>
                            <option>Incoming</option>
                            <option>Outgoing</option>
                        </select>
                    </div>
                </div>
                <div class="row container mt-3">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">From</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="from" class="form-control" required>
                    </div>
                </div>
                <div class="row container mt-3">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">To</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="to" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3 shadow">Generate Report</button>
            </form>
        </div>
    @endif
    @if(request()->query('type') == 'consigned')
        <div class="form-group mt-3">
            <form action="{{route('reports.generate', 'consigned')}}" method="post">
                @csrf
                <h5 class="my-3">Generate Consigned Spares Report</h5>
                <div class="row container">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">Type</label>
                    </div>
                    <div class="col-10">
                        <select name="type" id="" class="form-control" required>
                            <option value="">-</option>
                            <option>Incoming</option>
                            <option>Outgoing</option>
                        </select>
                    </div>
                </div>
                <div class="row container mt-3">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">From</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="from" class="form-control" required>
                    </div>
                </div>
                <div class="row container mt-3">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">To</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="to" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3 shadow">Generate Report</button>
            </form>
        </div>
    @endif
    @if(request()->query('type') == 'components')
        <div class="form-group mt-3">
            <form action="{{route('reports.generate', 'components')}}" method="post">
                @csrf
                <h5 class="my-3">Generate Components Report</h5>
                <div class="row container">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">Type</label>
                    </div>
                    <div class="col-10">
                        <select name="type" id="" class="form-control" required>
                            <option value="">-</option>
                            <option>Incoming</option>
                            <option>Outgoing</option>
                        </select>
                    </div>
                </div>
                <div class="row container mt-3">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">From</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="from" class="form-control" required>
                    </div>
                </div>
                <div class="row container mt-3">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">To</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="to" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3 shadow">Generate Report</button>
            </form>
        </div>
    @endif
    @if(request()->query('type') == 'equipment')
        <div class="form-group mt-3">
            <form action="{{route('reports.generate', 'equipment')}}" method="post">
                @csrf
                <h5 class="my-3">Generate Equipment Report</h5>
                <div class="row container">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">From</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="from" class="form-control" required>
                    </div>
                </div>
                <div class="row container mt-3">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">To</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="to" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3 shadow">Generate Report</button>
            </form>
        </div>
    @endif
    @if(request()->query('type') == 'tools')
        <div class="form-group mt-3">
            <form action="{{route('reports.generate', 'tools')}}" method="post">
                @csrf
                <h5 class="my-3">Generate Tools Report</h5>
                <div class="row container">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">From</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="from" class="form-control" required>
                    </div>
                </div>
                <div class="row container mt-3">
                    <div class="col-2">
                        <label for="#to" class="font-weight-bold">To</label>
                    </div>
                    <div class="col-10">
                        <input type="date" name="to" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3 shadow">Generate Report</button>
            </form>
        </div>
    @endif
@endsection
