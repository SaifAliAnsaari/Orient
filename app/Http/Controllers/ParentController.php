<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Cookie;

class ParentController extends Controller
{
    public $check_employee_rights;
    public $redirectUrl = null;
    public $notif_counts = 0;
    public $notif_data;
    public $all_notification;
    public $approval_notif_counts = 0;
    public $unread_notif_approval;
    public $approval_notif;

    public function __construct(){
    }

    public function VerifyRights(){
        $this->getAccRights();
        if(!DB::table('access_rights')->whereRaw('employee_id = '. Auth::user()->id . ' and access = "/'.explode('/', url()->current())[3].'"')->first()){
            DB::table('access_rights')->whereRaw('employee_id = '. Auth::user()->id . ' and access = "/home" and access != "/edit_profile"')->first() ? $this->redirectUrl = "/home" : (DB::table('access_rights')->whereRaw('access != "/edit_profile" and employee_id = '. Auth::user()->id)->first() ? $this->redirectUrl = DB::table('access_rights')->whereRaw('access != "/edit_profile" and employee_id = '. Auth::user()->id)->first()->access : $this->redirectUrl = "/logout" );
        }
    }

    public function getAccRights(){
        $this->check_employee_rights = DB::table('access_rights')->where('employee_id', Auth::user()->id)->get();
    }

    public function get_notif_data(){

        // $check = DB::table('subscribed_notifications as sn')->selectRaw('GROUP_CONCAT(notification_code_id) as notifications_codes')->whereRaw('web = 1 AND emp_id = '. Auth::user()->id)->first();
        //print_r($check); die;



        
        // if(DB::table('subscribed_notifications')->whereRaw('notification_code_id = 103 AND web = 1 AND emp_id = '.Auth::user()->id)->first()){
        //     $this->approval_notif = DB::table('approval_notif as an')->selectRaw('id, cvr_id, notif_to, approval_by, approval, remarks, read_notif, created_at, (Select name from users where id = an.approval_by) as approval_by_name')->whereRaw('notif_to = "'.Auth::user()->id.'" AND read_notif = 0 AND cvr_id IS NOT NULL')->get();

        //     $this->unread_notif_approval = DB::table('approval_notif as an')->selectRaw('id, cvr_id, notif_to, approval_by, approval, remarks, read_notif, created_at, (Select name from users where id = an.approval_by) as approval_by_name')->whereRaw('notif_to = "'.Auth::user()->id.'" AND cvr_id IS NOT NULL')->get();
        // }else{
            $this->approval_notif = [];
            $this->unread_notif_approval = [];
        //}

        // SVR approval Notif
        // if(DB::table('subscribed_notifications')->whereRaw('notification_code_id = 106 AND web = 1 AND emp_id = '.Auth::user()->id)->first()){
        //     $this->approval_notif = DB::table('approval_notif as an')->selectRaw('id, svr_id, notif_to, approval_by, approval, remarks, read_notif, created_at, (Select name from users where id = an.approval_by) as approval_by_name')->whereRaw('notif_to = "'.Auth::user()->id.'" AND read_notif = 0 AND svr_id IS NOT NULL')->get();

        //     $this->unread_notif_approval = DB::table('approval_notif as an')->selectRaw('id, svr_id, notif_to, approval_by, approval, remarks, read_notif, created_at, (Select name from users where id = an.approval_by) as approval_by_name')->whereRaw('notif_to = "'.Auth::user()->id.'" AND svr_id IS NOT NULL')->get();
        // }else{
        //     $this->approval_notif = [];
        //     $this->unread_notif_approval = [];
        // }
        
        //echo "<pre>"; print_r($this->approval_notif); die;
        //if($check->notifications_codes){

            //Counts
            // $this->notif_counts = DB::table('notifications_list as nl')->selectRaw('Count(*) as counts')->whereRaw('code IN ('.DB::table('subscribed_notifications as sn')->selectRaw('GROUP_CONCAT(notification_code_id) as notifications_codes')->whereRaw('web = 1 AND emp_id = '. Auth::user()->id)->first()->notifications_codes.') AND id NOT IN (Select notif_id from notification_read_status where emp_id = "'.Auth::user()->id.'")')->first();
            

            // $this->notif_data = DB::table('notifications_list as nl')->selectRaw('id, code, message, customer_id, cvr_id, created_at, CASE
            // WHEN customer_id IS NOT NULL THEN (Select company_name from customers where id = nl.customer_id) ELSE "CVR" END AS customer_nameOrCvr')->whereRaw('code IN ('.DB::table('subscribed_notifications as sn')->selectRaw('GROUP_CONCAT(notification_code_id) as notifications_codes')->whereRaw('web = 1 AND emp_id = '. Auth::user()->id)->first()->notifications_codes.') AND id NOT IN (Select notif_id from notification_read_status where emp_id = "'.Auth::user()->id.'")')->orderBy('id','DESC')->take(4)->get();


            // //  //Show all notifications
            // $this->all_notification =  DB::table('notifications_list as nl')->selectRaw('id, code, message, customer_id, cvr_id, created_at, CASE
            // WHEN customer_id IS NOT NULL THEN (Select company_name from customers where id = nl.customer_id) ELSE "CVR" END AS customer_nameOrCvr')->whereRaw('code IN ('.DB::table('subscribed_notifications as sn')->selectRaw('GROUP_CONCAT(notification_code_id) as notifications_codes')->whereRaw('web = 1 AND emp_id = '. Auth::user()->id)->first()->notifications_codes.')')->orderBy('id','DESC')->get();

            $this->notif_counts = DB::table('notifications_list as nl')->selectRaw('Count(*) as counts')->whereRaw('notif_to = "'.Auth::user()->id.'" AND id NOT IN (Select notif_id from notification_read_status where emp_id = "'.Auth::user()->id.'")')->first();

            $this->notif_data = DB::table('notifications_list as nl')->selectRaw('id, code, message, customer_id, svr_id, cvr_id, created_at, CASE
             WHEN customer_id IS NOT NULL THEN (Select company_name from customers where id = nl.customer_id) ELSE "CVR" END AS customer_nameOrCvr')->whereRaw('notif_to =  '.Auth::user()->id.' AND id NOT IN (Select notif_id from notification_read_status where emp_id = "'.Auth::user()->id.'")')->orderBy('id','DESC')->take(4)->get();


             $this->all_notification =  DB::table('notifications_list as nl')->selectRaw('id, code, message, customer_id, svr_id, cvr_id, created_at, CASE WHEN customer_id IS NOT NULL THEN (Select company_name from customers where id = nl.customer_id) ELSE "CVR" END AS customer_nameOrCvr')->whereRaw('notif_to =  '.Auth::user()->id)->orderBy('id','DESC')->get();

            //echo "<pre>"; print_r($this->all_notification); die;

        // }else{
        //     $this->notif_counts = "";
        //     $this->notif_data = [];
        //     $this->all_notification = [];
        // }
 

    }

}
