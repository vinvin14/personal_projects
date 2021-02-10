<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 26/11/2020
 * Time: 3:32 PM
 */

namespace App\Http\Controllers\api;
use App\Http\Controllers\UtilitiesController;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }

    public function index()
    {
        $Customers = Customer::orderBy('name', 'ASC')->get();
        if(empty($Customers->toArray()))
        {
            return response()->json('No Record(s) Found!', 404);
        }
        return response()->json($Customers, 200);
    }
    public function show($id)
    {
        $Customer = Customer::find($id);
        if(empty($Customer))
        {
            return response()->json('Customer not found!', 404);
        }
        return response()->json($Customer, 200);
    }
    public function update($id, Request $request)
    {
        $Customer = Customer::find($id);
        $util = new UtilitiesController();
        $duplicate = Customer::where([
            'name' => $request->input('name'),
        ])->first();

        if(! empty($duplicate))
        {
            if($Customer->name != $request->input('name'))
            {
                return response()->json('Entry that you want to push through will cause duplication!!', 404);
            }
        }
        if(empty($Customer))
        {
            return response()->json('Customer not found!', 404);
        }
        try
        {
            $Customer->update($request->post());
            return response()->json('Customer Successfully Updated!', 200);
        }
        catch (\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'CUSUPDATE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function store(Request $request)
    {
        $input = $request->post();
        $util = new UtilitiesController();
        $duplicate = Customer::where([
            'name' => $input['name'],
        ])->first();
        if(empty($input['address']))
        {
            $input['address'] = 'No Data';
        }
        if(! empty($duplicate))
        {
            return response()->json('Customer has been added previously!', 404);
        }
        try
        {
            $input['dateAdded'] = date('m-d-y');
            Customer::create($input);
            return response()->json('You have added new Customer!', 200);
        }
        catch(\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'CUSSTORE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function destroy($id)
    {
        $unit = Customer::find($id);
        if(empty($unit))
        {
            return response()->json('Customer not found!', 404);
        }
        $unit->delete();
        return response()->json('Successfully deleted!', 200);
    }
}
