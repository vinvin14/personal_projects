<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16/01/2021
 * Time: 10:30 AM
 */

namespace App\Services;


use App\Models\Consumable;

class ConsumableServices
{
    public function ifExist($data)
    {
        $consumable = Consumable::where(['partNumber' => $data['partNumber'], 'description' => $data['description']])->first();
        if(empty($consumable)) {
            return false;
        }
        return true;
    }
}
