@extends('layouts.main')
@section('title', 'Resources')
@section('content2')
    <h2>Resources</h2>
    <nav class="nav border-bottom reference-nav">
        <a class="nav-link
            @if(@$reference_nav_selected == 'consumables')
                reference-nav-active
            @endif
            " href="{{route('consumables')}}">Consumables
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'components')
                reference-nav-active
            @endif
            " href="{{route('components')}}">Components
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'consigned')
                reference-nav-active
            @endif
            " href="{{route('consigned')}}">Consigned Spares
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'equipment')
                reference-nav-active
            @endif
            " href="{{route('equipment')}}">Equipment
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'tools')
                reference-nav-active
            @endif
            " href="{{route('tools')}}">Tools
        </a>
    </nav>
    <div class="px-1">
        @yield('content3')
    </div>
@endsection
