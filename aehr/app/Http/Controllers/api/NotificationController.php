<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 17/12/2020
 * Time: 11:58 AM
 */

namespace App\Http\Controllers\api;


use Illuminate\Support\Facades\DB;

class NotificationController
{
    public function notificationCount()
    {
        return DB::table('notification')->where('status', 0)->count();
    }
    public function notificationInfo()
    {
        return DB::table('notification')->where('status', 0)->orderBy('created', 'DESC')->get();
    }
}
