<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15/11/2020
 * Time: 9:42 PM
 */

namespace App\Http\Controllers;


use App\Models\Error;
use Illuminate\Support\Facades\DB;

class UtilitiesController
{
    public function createError($data)
    {
        list($user, $module, $details ) = $data;
        Error::create([
            'user' => $user,
            'module' => $module,
            'details' => $details,
        ]);
    }
    public function wrapMessage($code, $message)
    {
        switch ($code)
        {
            case 'success':
                return '<div class="alert alert-success alert-dismissible fade show"><strong>Success!</strong> ' . $message . '</div>';
                break;
            case 'error':
                return '<div class="alert alert-danger alert-dismissible fade show"><strong>Error!</strong> ' . $message . '</div>';
                break;
            case 'warning':
                return '<div class="alert alert-warning alert-dismissible fade show"><strong>Warning!</strong> ' . $message . '</div>';
                break;
            case 'info':
                return '<div class="alert alert-info alert-dismissible fade show"><strong>FYI!</strong> ' . $message . '</div>';
                break;
        }
    }
    public function clean($string)
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
    public function cleanExSpace($string)
    {
        return preg_replace('/[^\da-z ]/i', '', $string);
    }
    public function cleanAndSpaces($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
    public function cleanExHyphen($string)
    {
        return preg_replace('/[^ \w-]/', '', $string);
    }
    public function decimal($number)
    {
        list($whole, $decimal) = explode('.', $number);
        return $whole.'.'.$decimal[0].$decimal[1];
    }
    public function createNotification($data)
    {
        DB::table('notification')
                ->insert([
                    'item' => $data['item'],

                ]);
    }
}
