@extends('layouts.main')
@section('title', 'Item Movements')
@section('content2')
    <h2>Item Movements</h2>
    <nav class="nav border-bottom reference-nav">
        <a class="nav-link
            @if(@$reference_nav_selected == 'consumables')
            reference-nav-active
@endif
            " href="{{route('movement.consumables')}}">Consumables
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'components')
            reference-nav-active
@endif
            " href="{{route('movement.components')}}">Components
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'consigned')
            reference-nav-active
@endif
            " href="{{route('movement.consigned')}}">Consigned Spares
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'equipment')
            reference-nav-active
@endif
            " href="{{route('movement.equipment')}}">Equipment
        </a>
        <a class="nav-link
            @if(@$reference_nav_selected == 'tools')
            reference-nav-active
@endif
            " href="{{route('movement.tools')}}">Tools
        </a>
    </nav>
    <div class="px-1">
        @yield('content3')
    </div>
@endsection
