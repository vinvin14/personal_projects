<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferenceCustomerController extends Controller
{
    private $request, $util;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->util = new UtilitiesController();
    }
    public function index()
    {
        $customers = Customer::query()->orderBy('name', 'ASC')->paginate(10);
        return view('references.customer.index', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'customers'])
            ->with(compact('customers'));
    }
    public function update($id)
    {
        $customer = Customer::query()->findOrFail($id);
        return view('references.customer.update', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'customers'])
            ->with(compact('customer'));
    }
    public function upsave($id)
    {
        $customer = Customer::query()->findOrFail($id);
        if($customer->name != $this->request->input('name')) {
            if(! empty(Customer::query()->where('name', $this->request->input('name'))->first())) {
                return redirect(route('reference.customer.update', $id))
                    ->with('error', "$customer->name has been added previously!");
            }
        }
        try {
            $customer->update($this->request->post());
            return redirect(route('reference.customers'))
                ->with('response', "$customer->name has been updated!");
        } catch (\Exception $exception) {
            $this->util->createError([
                $_SESSION['user'],
                'REFERENCE_CUSTOMER',
                $exception->getMessage()
            ]);
            return redirect(route('reference.customer.update', $id))
                ->with('error', 'Something went wrong Please contact your Administrator!');
        }

    }
    public function create()
    {
        $existing_customer = Customer::query()->orderBy('name', 'ASC')->paginate(10);
        return view('references.customer.create', ['sidebar_selected' => 'References', 'reference_nav_selected' => 'customers'])
            ->with(compact('existing_customer'));
    }
    public function store()
    {
        $input = $this->request->except('_token');
        $duplicate = Customer::query()->where('name', $input['name'])->first();
        if(!empty($duplicate)) {
            return redirect(route('reference.customer.create'))->with('error', "$duplicate->name has been added previously!");
        }
        $customer = Customer::query()->create($input);
        return redirect(route('reference.customer.create'))->with('response', "$customer->name has been added to our records!");
    }
    public function destroy($id)
    {
        $customer = Customer::query()->findOrFail($id);
        $customer->delete();
        return redirect(route('reference.customers'))->with('response', 'Customer successfully deleted!');
    }
    public function search(Request $request)
    {
        $results = DB::table('customers')
                        ->where('name', 'LIKE', '%'.$request->get('keyword').'%')
                        ->orWhere('customerID', 'LIKE', '%'.$request->get('keyword').'%')
                        ->paginate(10);
        return view('references.customer.search_results', ['customers' => $results, 'total_results' => count($results), 'sidebar_selected' => 'References', 'reference_nav_selected' => 'customers']);

    }
}
