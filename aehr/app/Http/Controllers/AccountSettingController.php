<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountSettingController extends Controller
{
    public function index()
    {
        return view('account_setting.index', ['account_settings_selected' => 'account_details']);
    }
    public function password()
    {
        $current_password = DB::table('accounts')->select('password')->where('username', $_SESSION['user'])->first();
        return view('account_setting.password', ['account_settings_selected' => 'password', 'current_password' => $current_password]);
    }
    public function change_password(Request $request)
    {
        $input = $request->except('_token');
        $account = DB::table('accounts')
                        ->where('username', $input['requestor']);
        if(empty($account->first())) {
            return redirect(route('account.password'))->with('error', 'Account not found!');
        }

        if($input['new_password'] != $input['confirm_new_password']) {
            return redirect(route('account.password'))
                ->with(['current_password' => $input['current_password'], 'new_password' => $input['new_password'], 'confirm_new_password' => $input['confirm_new_password']])
                ->with('error', 'New and Confirm Password does not match!');
        }
        if(! password_verify($input['current_password'], $account->first()->password)) {
            return redirect(route('account.password'))
                ->with(['current_password' => $input['current_password'], 'new_password' => $input['new_password'], 'confirm_new_password' => $input['confirm_new_password']])
                ->with('error', 'Incorrect Current Password!');
        }

        $account->update(['password' => Hash::make($input['new_password'])]);
        return redirect(route('account.password'))->with('response', 'Password successfully updated!');
    }
    public function notification()
    {
        $data['email_recipient'] = DB::table('emailtonotify')->first();
        return view('account_setting.notification', ['data' => $data, 'account_settings_selected' => 'notification']);
    }
    public function notification_save($id, Request $request)
    {
        $input = $request->except('_token');

        try {
            DB::table('emailtonotify')
                ->where('id', $id)
                ->update(['email' => $input['email']]);
            return redirect(route('account.notification'))->with('response', 'Email successfully updated!');
        } catch (\Exception $exception) {
            return redirect(route('account.notification'))->with('error', 'Request Denied! Changes were not saved!');
        }
//        if(! $update) {
//            return redirect(route('account.notification'))->with('error', 'Request Denied! Changes were not saved!');
//        }

    }
}
