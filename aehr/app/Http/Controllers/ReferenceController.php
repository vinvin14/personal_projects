<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15/11/2020
 * Time: 8:34 PM
 */

namespace App\Http\Controllers;


use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferenceController
{
    public function index()
    {
        $unit = Unit::orderBy('unit', 'ASC')->paginate(8);
        return view('references.unit.index', ['data' => $unit, 'sidebar_selected' => 'References', 'reference_nav_selected' => 'units']);
    }
    public function boardtype()
    {
        return view('references.boardtype.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'boardtypes']);
    }
    public function systemtype()
    {
        return view('references.systemtype.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'systemtypes']);
    }
    public function typeofservice()
    {
        return view('references.typeofservice.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'typeofservices']);
    }
    public function customer()
    {
        return view('references.customer.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'customers']);
    }
    public function location()
    {
        return view('references.location.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'location']);
    }
    public function purpose()
    {
        return view('references.purpose.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'purpose']);
    }
    public function fe()
    {
        return view('references.fse.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'fse']);
    }


}
