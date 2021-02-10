<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 23/12/2020
 * Time: 7:16 AM
 */

namespace App\Http\Controllers;


use App\Mail\NotificationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailController
{
    private $notification, $util;
    public function __construct()
    {
        $this->notification = new NotificationController();
        $this->util = new UtilitiesController();
    }

    function resources_notifications($notifications)
    {
        if($notifications->toArray() != null)
        {
            $title = 'List of all Critical Resources Item';
            $counter = 0;
            $list = [];
            foreach ($notifications as $row)
            {
                $counter++;
                $message = "$counter. $row->details from $row->origin dated: $row->created";
                array_push($list, $message);
            }
//         new NotificationMail(['title' => $title, 'message' => $list, 'from' => 'resources_notifications']);
            try
            {
                $email = DB::table('emailtonotify')->first();
                Mail::to($email->email)->send(new NotificationMail(['title' => $title, 'message' => $list, 'from' => 'resources_notifications']));
                return 200;
            }
            catch (\Exception $exception)
            {
                $this->util->createError([
                    $_SESSION['user'],
                    'EMAILSENDING',
                    $exception->getMessage()
                ]);
                return 400;
            }
        }
        else
        {
            return 201;
        }
    }
}
