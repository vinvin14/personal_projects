<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 09/02/2021
 * Time: 4:42 PM
 */

namespace App\Services;


use Illuminate\Support\Facades\DB;

class ActivityLogServices
{
    function show_logs($account)
    {

    }
    function create($log)
    {
        DB::table('activity_logs')
                ->insert($log);
    }
}
