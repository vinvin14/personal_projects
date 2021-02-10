<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class AccountController extends Controller
{
    public function index()
    {
        return view('login.index');
    }
    public function login(Request $request)
    {
        $currUser = Account::where('username', $request->input('username'))->first();

        if(empty($currUser))
        {
            return Redirect::route('login')
                ->with('error', 'Account '.$request->input('username').' does not exist!')
                ->withInput();
        }
        if($currUser->account_permission < 1) {
            return redirect(route('login'))
                    ->with('error', 'Account hasn\'t been approved!')
                    ->withInput();
        }

        if(password_verify($request->input('password'), $currUser->password) === false)
        {
            return Redirect::route('login')
                    ->with('error', 'Invalid Username or Password!')
                    ->withInput();
        }

        //set token
        $token = $this->createToken();
        $currUser->update(['token' => $token]);

        //set sessions
        $_SESSION['user'] = $currUser->username;
        $_SESSION['token'] = $token;
        $_SESSION['role'] = $currUser->role;
        //set cookie for logging out
        setcookie("currently_login", 'yes', time()+43200);
        $this->initAccessLinks($currUser->role);
        return redirect('/dashboard');
    }
    public function createToken()
    {
        $token = str_shuffle('qkz@-!2l9#1aeWx');
        $duplicate = Account::where('token', $token)->count();

        while($duplicate > 0)
        {
            $token = str_shuffle($token);
        }
        return $token;
    }
    public function logout()
    {
        session_destroy();
        return redirect('/');
    }

    public function initAccessLinks($role)
    {
        switch ($role)
        {
            case 'superadmin':
                $_SESSION['sidebar_links'] = [
                    ['label' => 'Dashboard', 'link' => '/dashboard', 'icon' => 'fas fa-home'],
//                    ['label' => 'Customers', 'link' => '/customers', 'icon' => 'fas fa-users'],
                    ['label' => 'Repairs', 'link' => '/repairs', 'icon' => 'fas fa-cogs'],
                    ['label' => 'Item Movements', 'link' => '/movement/consumables', 'icon' => 'fas fa-truck-loading'],
//                    ['label' => 'Item Movements', 'link' => '/movement/consigned', 'icon' => 'fas fa-truck-loading'],
                    ['label' => 'Resources', 'link' => '/resources', 'icon' => 'fas fa-coins'],
                    ['label' => 'References', 'link' => route('reference.units'), 'icon' => 'fas fa-book'],
                    ['label' => 'Reports', 'link' => '/reports', 'icon' => 'fas fa-clipboard-list'],
                    ['label' => 'Notifications', 'link' => '/notifications', 'icon' => 'fas fa-clipboard-list'],
                    ['label' => 'Account Management', 'link' => '/account-management', 'icon' => 'fa fa-users'],
                ];
                break;
            case 'admin':
                $_SESSION['sidebar_links'] = [
                    ['label' => 'Dashboard', 'link' => '/dashboard', 'icon' => 'fas fa-home'],
//                    ['label' => 'Customers', 'link' => '/customers', 'icon' => 'fas fa-users'],
                    ['label' => 'Repairs', 'link' => '/repairs', 'icon' => 'fas fa-cogs'],
                    ['label' => 'Item Movements', 'link' => '/movement/consumables', 'icon' => 'fas fa-truck-loading'],
//                    ['label' => 'Item Movements', 'link' => '/movement/consigned', 'icon' => 'fas fa-truck-loading'],
                    ['label' => 'Resources', 'link' => '/resources', 'icon' => 'fas fa-coins'],
                    ['label' => 'References', 'link' => route('reference.units'), 'icon' => 'fas fa-book'],
                    ['label' => 'Reports', 'link' => '/reports', 'icon' => 'fas fa-clipboard-list'],
                    ['label' => 'Notifications', 'link' => '/notifications', 'icon' => 'fas fa-clipboard-list'],
                ];
                break;

            case 'encoder':
                $_SESSION['sidebar_links'] = [
                    ['label' => 'Dashboard', 'link' => '/dashboard', 'icon' => 'fas fa-home'],
//                    ['label' => 'Customers', 'link' => '/customers', 'icon' => 'fas fa-users'],
                    ['label' => 'Repairs', 'link' => '/repairs', 'icon' => 'fas fa-cogs'],
                    ['label' => 'Item Movements', 'link' => '/movement/consumables', 'icon' => 'fas fa-truck-loading'],
//                    ['label' => 'Item Movements', 'link' => '/movement/consigned', 'icon' => 'fas fa-truck-loading'],
                    ['label' => 'Resources', 'link' => '/resources', 'icon' => 'fas fa-coins'],
                    ['label' => 'Notifications', 'link' => '/notifications', 'icon' => 'fas fa-clipboard-list'],
                ];
                break;

        }
    }
    public function register()
    {
        return view('register');
    }
    public function register_save(Request $request)
    {
        $input = $request->except('_token');
        $util = new UtilitiesController();
        $duplicate = DB::table('accounts')
                            ->whereUsername($input['username'])
                            ->first();

        if($duplicate) {
            return redirect()->back()
                             ->with('error', 'Username Already Exist!')
                             ->withInput();
        }
        if($input['password'] != $input['confirm_password']) {
            return redirect()->back()
                            ->with('error', 'Password and Confirm Password does not match!')
                            ->withInput();
        }
        try {
            $input['password'] = Hash::make($input['password']);
            $new_user = Account::query()->create($input);
            return redirect(route('register'))
                        ->with('response', "$new_user->username account has been successfully registered and is subject for approval from Admin!");
        } catch (\Exception $exception) {
            $util->createError([
                'New User',
                'REGISTRATION',
                $exception->getMessage()
            ]);
            return redirect(route('register'))
                    ->with('error', 'Oops something went wrong! Please contact your administrator.');
        }
    }

}
