@extends('layouts.main')
@section('title', 'References')
@section('content2')
    <h2>References</h2>
    <nav class="nav border-bottom reference-nav">
        <a class="nav-link
            @if(@$reference_nav_selected == 'units')
            reference-nav-active
            @endif
        " href="{{route('reference.units')}}">Units
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'faultcodes')
                reference-nav-active
            @endif
        " href="{{route('reference.faultcodes')}}">Fault Codes
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'systemtypes')
                reference-nav-active
            @endif
            " href="{{route('reference.systemtypes')}}">System Types
        </a>
        {{--<a class="nav-link--}}
            {{--@if(@$reference_nav_selected == 'boardtypes')--}}
                {{--reference-nav-active--}}
            {{--@endif--}}
            {{--" href="{{route('reference.boardtypes')}}">Board Types--}}
        {{--</a>--}}
        <a class="nav-link
            @if(@$reference_nav_selected == 'typeofservices')
                reference-nav-active
            @endif
            " href="{{route('reference.typeofservices')}}">Type of Services
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'customers')
                reference-nav-active
            @endif
        " href="{{route('reference.customers')}}">Customers
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'location')
                reference-nav-active
            @endif
            " href="{{route('reference.locations')}}">Location
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'purpose')
                reference-nav-active
            @endif
            " href="{{route('reference.purposes')}}">Purpose
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'fse')
                reference-nav-active
            @endif
            " href="{{route('reference.fses')}}">Field Service Engineers
        </a>
    </nav>
    <div class="px-1">
        @yield('content3')
    </div>
@endsection
