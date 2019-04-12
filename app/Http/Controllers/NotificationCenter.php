<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use URL;
use Auth;

class NotificationCenter extends ParentController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function notification_prefrences(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $employees = DB::table('users')->get();
        $notifications_name = DB::table('notifications_code')->get();
        return view('notif.notification_pref', ['emp' => $employees, 'notifications_code' => $notifications_name, 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
    }

    public function notif_pref_against_emp($id){
        echo json_encode(DB::table('subscribed_notifications')->where('emp_id', $id)->get());
    }

    public function save_pref_against_emp(Request $request){
        if(DB::table('subscribed_notifications')->where('emp_id', $request->emp_id)->first()){
            $delete_existing = DB::table('subscribed_notifications')->where('emp_id', $request->emp_id)->delete();
            if($delete_existing){
                foreach($request->notifications as $notifications){
                    $emailProp = 0;
                    $webProp = 0;
                    if(isset($notifications["properties"])){
                        foreach($notifications["properties"] as $props){
                            if($props == "email"){
                                $emailProp = 1;
                            }else{
                                $webProp = 1;
                            }
                        }
                        $insert = DB::table('subscribed_notifications')->insert([
                            'notification_code_id' => $notifications['code'],
                            'web' => $webProp,
                            'email' => $emailProp,
                            'emp_id' => $request->emp_id
                        ]);
                    }
                }
                echo json_encode('success');
            }else{
                echo json_encode('failed');
            }
        }else{
            foreach($request->notifications as $notifications){
                $emailProp = 0;
                $webProp = 0;
                foreach($notifications["properties"] as $props){
                    if($props == "email"){
                        $emailProp = 1;
                    }else{
                        $webProp = 1;
                    }
                }
                $insert = DB::table('subscribed_notifications')->insert([
                    'notification_code_id' => $notifications['code'],
                    'web' => $webProp,
                    'email' => $emailProp,
                    'emp_id' => $request->emp_id
                ]);
            }
            echo json_encode('success');
        }
    }

    public function read_notif_four(Request $request){
        DB::table('approval_notif')->where('notif_to', Auth::user()->id)->update(['read_notif' => 1]);
        if($request->notif_ids != ""){
            foreach($request->notif_ids as $notifications){
                DB::table('notification_read_status')->whereRaw('notif_id = "'.$notifications.'" AND emp_id = '.Auth::user()->id)->delete();
                DB::table('notification_read_status')->insert([
                    'notif_id' => $notifications,
                    'emp_id' => Auth::user()->id
                ]);
            }
        }
    }

    public function notifications(){
        parent::VerifyRights();
        parent::get_notif_data();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $read_all_basic_apprval = DB::table('approval_notif')->where('notif_to', Auth::user()->id)->update(['read_notif' => 1]);
        if(!empty($this->all_notification)){
            foreach($this->all_notification as $notif){
                DB::table('notification_read_status')->insert([
                    'notif_id' => $notif->id,
                    'emp_id' => Auth::user()->id
                ]);
            }
        }
        return view('notif.notifications', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
        
    }
}
