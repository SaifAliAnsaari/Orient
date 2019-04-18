<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use URL;
use Auth;

class ComplaintsManagment extends ParentController
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

    public function complaints_settings(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $employees = DB::table('users')->get();
        return view('complaints.settings', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval, 'employees' => $employees]);
    }

    public function save_complain_type(Request $request){
        if($request->operation == 'update'){
            if(DB::table('complain_type')->where('complain_head', $request->head)->first()){
                echo json_encode('already exist');
            }else{
                $test = $request->id;
                $update = DB::table('complain_type')->where('id', $request->id)->update([
                    'complain_updated_by' =>  Auth::user()->id,
                    'complain_head' => $request->head,
                    'complain_tat' => $request->tat,
                    'assign_to' => json_encode($request->assign_to),
                    'complain_updated_at' => date('Y-m-d H:i:s')
                    ]);
                if($update){
                    echo json_encode('success');
                }else{
                    echo json_encode('failed');
                }
            }
        }else{
            if(DB::table('complain_type')->where('complain_head', $request->head)->first()){
                echo json_encode('already exist');
            }else{
                $insert = DB::table('complain_type')->insert([
                    'complain_added_by' =>  Auth::user()->id,
                    'complain_head' => $request->head,
                    'complain_tat' => $request->tat,
                    'assign_to' => json_encode($request->assign_to),
                    'created_at' => date('Y-m-d H:i:s')
                    ]);
                if($insert){
                    echo json_encode('success');
                }else{
                    echo json_encode('failed');
                }
            } 
        }
        
    }

    public function complains_type_list(){
        $complain_type = DB::table('complain_type')->get();
        $users = DB::table('users')->get();
        $data = [];
        $members = [];
        $counter = 0;
        
        foreach($complain_type as $complains){
            $data[$counter]['id'] = $complains->id;
            $data[$counter]['head'] = $complains->complain_head;
            $data[$counter]['tat'] = $complains->complain_tat;

            if($complains->assign_to){
                $members = [];
                $count_mem = 0;
                foreach(json_decode($complains->assign_to, true) as $mem){
                    foreach($users as $employees){
                        if($mem == $employees->id){
                            $members[$count_mem]['mem_name'] = $employees->name;
                        }
                    }
                    $count_mem ++;
                }
            }
            $data[$counter]['assign_to'] = $members;
            $counter ++;
        }

        echo json_encode($data);
    }

    public function get_complain_type_data(Request $request){
        $core = DB::table('complain_type')->where('id', $request->id)->first();
        $users = DB::table('users')->get()->toArray();
        $mem = [];
        $counter = 0;

        $names = [];
        array_map(function($item) use($users, &$names, &$counter){
            
            $names[$counter]['name'] = $users[array_search($item, array_column($users, "id"))]->name;
            $names[$counter]['ids'] = $users[array_search($item, array_column($users, "id"))]->id;
            $counter ++;
        }, json_decode($core->assign_to, true));

        // $data = [];
        // $data['core'] = $core;
        // $data['names'] = $names; 
        echo json_encode(array('core' => $core, 'names' => $names));
    }

    public function delete_complain_type(Request $request){
        $delete = DB::table('complain_type')->where('id', $request->id)->delete();
        if($delete){
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
    }




    public function generate_complaints(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $cust = DB::table('customers')->get();
        $complain_type = DB::table('complain_type')->get();
        return view('complaints.generate', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval, 'cust' => $cust, 'complain_type' => $complain_type]);
    }

    public function save_complain(Request $request){
        $insert = DB::table('complains')->insert([
            'customer_id' => $request->customers,
            'complain_id' => $request->complain_type,
            'remarks' => $request->description,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::user()->id
        ]);
        if($insert){
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
    }





    public function complaints_list(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        return view('complaints.complaints_list', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
    }

    public function get_complains_list(){
        echo json_encode(array('info' => DB::table('complains as com')->selectRaw('id, Date(created_at) as date, created_at, resolved, (Select name from users where id = com.created_by) as created_by, (Select complain_head from complain_type where id = com.complain_id) as complain, (Select company_name from customers where id = com.customer_id) as customer, (Select complain_tat from complain_type where id = com.complain_id) as tat, (Select assign_to from complain_type where id = com.complain_id) as assign_to')->get(), 'id' => Auth::user()->id));
    }

    public function resolve_complain(Request $request){
        $update = DB::table('complains')->where('id', $request->id)->update([
            'resolved' => 1,
            'resolved_remarks' => $request->remarks
        ]);
        if($update){
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
    }

}
