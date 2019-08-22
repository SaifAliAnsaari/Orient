<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class IndustryManagement extends ParentController
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

    public function industries(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        return view('industry.manage_industry', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
    }

    public function save_industry(Request $request){
        if($request->operation == 'add'){
            if(DB::table('industry')->where('industry_name', $request->industry_name)->first()){
                echo json_encode('already_exist');
            }else{
                $insert = DB::table('industry')->insert([
                    'industry_name' => $request->industry_name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id
                ]);
                if($insert){
                    echo json_encode('success');
                }else{
                    echo json_encode('failed');
                }
            }
        }else{
            if(DB::table('industry')->whereRaw('industry_name = "'.$request->industry_name.'" AND id != '.$request->hidden_industry_id)->first()){
                echo json_encode('already_exist');
            }else{
                try{
                    DB::table('industry')->where('id', $request->hidden_industry_id)->update([
                        'industry_name' => $request->industry_name, 
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => Auth::user()->id
                        ]);
                    echo json_encode('success');
                }catch(\Illuminate\Database\QueryException $ex){ 
                    echo json_encode('failed'); 
                }
            }
        }
    }

    public function GetIndustries(){
        echo json_encode(DB::table('industry')->get());
    }

    public function GetSelectedIndustry($id){
        echo json_encode(DB::table('industry')->where('id', $id)->first());
    }

    public function delete_industry($id){
        $delete = DB::table('industry')->where('id', $id)->delete();
        if($delete){
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
    }
}
