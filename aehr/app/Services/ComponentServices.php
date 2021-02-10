<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 18/01/2021
 * Time: 4:46 PM
 */

namespace App\Services;


use App\Models\Component;

class ComponentServices
{
    public function ifExist($data)
    {
        $component = Component::where(['partNumber' => $data['partNumber'], 'description' => $data['description']])->first();
        if(empty($component)) {
            return false;
        }
        return true;
    }
}
