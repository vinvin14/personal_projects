<?php

namespace App\Http\Controllers;


use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountManagementController extends Controller
{
    public function index()
    {
        $pending_accounts = DB::table('accounts')
                            ->where('role', '!=', 'superadmin')
                            ->where('account_permission', 0)
                            ->orderBy('username', 'ASC')
                            ->paginate(10);
        $active_accounts = DB::table('accounts')
                            ->where('role', '!=', 'superadmin')
                            ->where('account_permission', 1)
                            ->orderBy('username', 'ASC')
                            ->paginate(10);


        return view('account_management.index')
                    ->with('sidebar_selected', 'Account Management')
                    ->with(compact('pending_accounts'))
                    ->with(compact('active_accounts'));
    }
    public function show($id)
    {
        $selected_user = Account::query()->findOrFail($id);

        return view('account_management.show')
                    ->with('sidebar_selected', 'Account Management')
                    ->with(compact('selected_user'));
    }
    public function approve($id, Request $request)
    {
        $input = $request->except('_token');
        $selected_user = Account::query()->findOrFail($id);
        $util = new UtilitiesController();

        try {
            $input['account_permission'] = 1;
            $selected_user->update($input);
            return redirect(route('account.management'))
                        ->with('response', "$selected_user->username Account has been reviewed and updated!");
        } catch (\Exception $exception) {
            $util->createError([
                'New User',
                'ACCOUNT APPROVAL',
                $exception->getMessage()
            ]);
            return redirect(route('register'))
                        ->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
    public function reset($id)
    {
        $util = new UtilitiesController();
        $curr_account = Account::query()->find($id);
        try {
            $curr_account->update(['password' => Hash::make('1234')]);
            return redirect(route('account.management.show', $id))
                ->with('response', "$curr_account->username's password has been reset to 1234!");
        } catch (\Exception $exception) {
            $util->createError([
                'New User',
                'REGISTRATION',
                $exception->getMessage()
            ]);
            return redirect(route('account.management'))
                ->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }
}
