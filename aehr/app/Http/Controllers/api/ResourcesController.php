<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 03/12/2020
 * Time: 7:12 PM
 */

namespace App\Http\Controllers\api;


use App\Models\Component;
use Illuminate\Support\Facades\DB;

class ResourcesController
{
    public function components()
    {
        $all = Component::orderBy('description', 'ASC')->get();
        if(empty($all))
        {
            return response()->json('No Record(s) Found!', 404);
        }
        return response()->json($all, 200);
    }
    public function createReplacementParts()
    {

    }
}
