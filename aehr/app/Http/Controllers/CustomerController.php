<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $data = Customer::orderBy('name', 'ASC')->get();
        return view('customers.index', ['data' => $data, 'sidebar_selected' => 'Repairs']);
    }
}
