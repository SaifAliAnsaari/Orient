<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use URL;
use Auth;

class AccessRights extends ParentController
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

     public function select_employee(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        return view('access_rights.employees_list', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
     }

     public function GetEmployeeListForRights(){
         echo json_encode(DB::table('users as u')->selectRaw('id, name, username, email, phone, cnic, (Case When (Select Count(*) from access_rights where employee_id = u.id) > 0 Then 1 Else 0 End) as rights')->get());
     }



     public function access_rights($id){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}

        $controllers = DB::table('controllers')->selectRaw('id, route_name, show_up_name, Heading')->get();
        $check = DB::table('access_rights')->where('employee_id', $id)->get();
       
         $counter = 0;

        $array_heading = array();
        foreach($controllers as $headings){
            $array_heading[$counter] = $headings->Heading;
            $counter++;
        }

        $array_heading = array_unique($array_heading);

        $data = array();
        $counter_two = 0;
        
        //echo '<pre>'; print_r($array_heading); die;
        foreach($array_heading as $arr_heading){
            $data[$counter_two]['heading'] = $arr_heading;
            $counter_three = 0;
            $array_routes = array();
            foreach($controllers as $routes){
                if($routes->Heading == $arr_heading){
                    $array_routes[$counter_three] = array('id' => $routes->id, 'name' => $routes->show_up_name, 'route' => $routes->route_name);
                    $counter_three++;
                }
            }
            $data[$counter_two]["detail"] = $array_routes;
            $counter_three = 0;
            $counter_two ++;
        }

        if(!$check->isEmpty()){
            return view('access_rights.access_rights', ['employee_id' => $id, 'controllers' => $data, "rights" => $check, 'check_rights' => $this->check_employee_rights, 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
        }else{
            return view('access_rights.access_rights', ['employee_id' => $id, 'controllers' => $data, "rights" => "", 'check_rights' => $this->check_employee_rights, 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
        }
     }

     public function saveAccessRights(Request $request){
        $check = DB::table('access_rights')->where('employee_id', $request->select_employee)->first();
        if($check){
            $delete_one_entry = DB::table('access_rights')->where('employee_id', $request->select_employee)->delete();
            if($delete_one_entry){
                foreach(explode(",", $request->access_route) as $routes){
                    $insert = DB::table('access_rights')->insert([
                        "employee_id" => $request->select_employee,
                        "access" => $routes
                    ]);
                }
                DB::table('logs')->insert([
                    'operation' => 'Access Rights',
                    'created_by' => Auth::user()->id,
                    'created_for' => $request->select_employee,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                echo json_encode('success');
            }else{
                echo json_encode('failed'); 
            }
        }else{
            foreach(explode(",", $request->access_route) as $routes){
                $insert = DB::table('access_rights')->insert([
                    "employee_id" => $request->select_employee,
                    "access" => $routes
                ]);
            }
            DB::table('logs')->insert([
                'operation' => 'Access Rights',
                'created_by' => Auth::user()->id,
                'created_for' => $request->select_employee,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            echo json_encode('success');
        }
     }
}
