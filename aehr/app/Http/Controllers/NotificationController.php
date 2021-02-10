<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function create($item, $item_description, $item_name, $stage, $user, $origin, $link)
    {
        switch ($stage)
        {
            case 'critical':
                $details = "$item_name | $item_description has reached critical stage";
                break;
            case 'depleted':
                $details = "Unfortunately, $item_name | $item_description has been depleted!";
                break;
        }
        if(empty(DB::table('notification')->where(['item' => $item, 'origin' => $origin, 'details' => $details, 'created' => date('Y-m-d')])->first()))
        {
            DB::table('notification')->insert([
                'item' => $item,
                'details' => $details,
                'origin' => $origin,
//                'created' => date('Y-m-d h:i:s'),
                'created' => date('Y-m-d'),
                'user' => $user,
                'link' => $link
            ]);
        }
    }
    public function info($info)
    {
        DB::table('notification')
            ->insert([
                'details' => $info['details'],
                'seen' => 'no',
                'type' => 'notification',
                'origin' => 'depreciation',
            ]);
    }
    public function index()
    {
        $data['unclosed'] = DB::table('notification')->where('status', 0)->get();
        $data['closed'] = DB::table('notification')->where('status', 1)->get();
        return view('notifications.index', ['data' => $data, 'sidebar_selected' => 'Notifications', 'reference_nav_selected' => 'faultcodes']);
    }
    public function resolve($id)
    {
        $query = Notification::findOrFail($id);
        $query->update(['status' => 1]);
        return redirect(route('notifications'))->with('response', 'Notification has been closed!');
    }
    public function resolveAll()
    {
        $email = new EmailController();
        $query = DB::table('notification')->where('status', 0)->select('id','details', 'created', 'origin')->get();

        $init_notifications = $email->resources_notifications($query);
        if($init_notifications == 200)
        {
            foreach ($query as $notification)
            {
                $this->updateDoneNotification($notification->id);
            }
            return redirect(route('notifications'))->with('response', 'Notification Email has been sent!');
        }
        if($init_notifications == 201)
        {
            return redirect(route('notifications'))->with('response', 'No Active notification(s) to be sent!');
        }
        return redirect(route('notifications'))->with('error', 'It seems that we have a problem!');

    }
    public function undoneNotifications()
    {
        return DB::table('notification')->where('status', 0)->get();
    }
    public function updateDoneNotification($id)
    {
        $query = Notification::findOrFail($id);
        $query->update(['status' => 1]);
    }

}
