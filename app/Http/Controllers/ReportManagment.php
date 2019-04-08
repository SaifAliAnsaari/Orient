<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use URL;
use Auth;

class ReportManagment extends ParentController
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

    //View
    public function new_cvr(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        return view('report_managment.new_cvr', ['categories' => DB::table('sub_categories')->get(), 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights]);
    }

    //Get Customers AND POC List
    public function GetCustForCVR(){
        echo json_encode(array(
            'customers' => DB::table('customers')->get(),
            'poc'  => DB::table('poc')->get()
        ));
    }


    //Save Modal POC
    public function save_poc_from_modal(Request $request){
        if(DB::table('poc')->where('email', $request->email)->first()){
            echo json_encode('already_exist');
        }else{
            $insert = DB::table('poc')->insertGetId([
                'poc_name' => $request->poc_name,
                'company_name' => $request->company_name,
                'job_title' => $request->jobTitle,
                'bussiness_ph' => $request->businessPH,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state
            ]);
            if($insert){
                echo json_encode($insert);
            }else{
                echo json_encode('failed');
            }
        }
    }


    //Save CVR
    public function save_cvr(Request $request){
        $insert_core = DB::table('cvr_core')->insertGetId([
            'report_created_at' => date('M d Y'),
            'report_created_by' =>  Auth::user()->id,
            'date_of_visit' => $request->date,
            'customer_visited' => $request->customer_id,
            'location' => $request->location,
            'time_spent' => $request->time_spent,
            'purpose_of_visit' => $request->purpose,
            'opportunity' => $request->opportunity,
            'bussiness_value' => $request->annualBusiness,
            'relationship' => $request->relationship,
            'description' => $request->description
            //'created_at' =>  date('Y-m-d H:i:s')
            ]);
        foreach($request->poc_list as $poc){
            $insert_poc = DB::table('cvr_poc')->insert([
                'poc_id' => $poc["poc_id"],
                'cvr_id' => $insert_core
            ]);
        }
        foreach($request->competition_list as $comp){
            $insert_comp = DB::table('cvr_competition')->insert([
                'cvr_id' => $insert_core,
                'name' => $comp["name"],
                'strength' => $comp['strength']
            ]);
        }
        foreach($request->products as $pro){
            $insert_pro = DB::table('cvr_products')->insert([
                'cvr_id' => $insert_core,
                'category_id' => $pro
            ]);
        }

        $insert_notification = DB::table('notifications_list')->insert([
            'code' => 102,
            'message' => 'New CVR added',
            'cvr_id' => $insert_core
        ]);

        if($insert_core){
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }

    }




    //CVR List
    public function cvr_list(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        return view('report_managment.cvr_list', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights]);
    }

    public function GetCVRList(){
        echo json_encode(DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->get());
    }




    //CVR Preview
    public function cvr_preview($id){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $core = DB::table('cvr_core as cc')->selectRaw('id, report_created_at, report_created_by, date_of_visit, customer_visited, location, time_spent, purpose_of_visit, opportunity, bussiness_value, relationship, description, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('id', $id)->first();
        if($core){
            $products = DB::table('cvr_products as cp')->selectRaw('id, category_id, (Select name from sub_categories where id = cp.category_id) as cat_name')->where('cvr_id', $id)->get();
            $pocs = DB::table('cvr_poc as c_p')->selectRaw('id, poc_id, (Select poc_name from poc where id = c_p.poc_id) as poc_name')->where('cvr_id', $id)->get();
            $competitions = DB::table('cvr_competition')->where('cvr_id', $id)->get();
            return view('report_managment.cvr_preview', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'core' => $core, 'products' => $products, 'poc' => $pocs, 'competition' => $competitions]);
        }else{
            return redirect('/');
        }
        
       
    }




    //Edit CVR
    public function edit_cvr($id){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $core = DB::table('cvr_core as cc')->where('id', $id)->first();
        if($core){
            return view('report_managment.edit_cvr', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'categories' => DB::table('sub_categories')->get(), 'id' => $id]);
        }else{
            return redirect('/');
        }
    }

    public function GetCurrentCvr($id){
        $core = DB::table('cvr_core as cc')->selectRaw('id, report_created_at, report_created_by, date_of_visit, customer_visited, location, time_spent, purpose_of_visit, opportunity, bussiness_value, relationship, description, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('id', $id)->first();
        $products = DB::table('cvr_products as cp')->selectRaw('id, category_id, (Select name from sub_categories where id = cp.category_id) as cat_name')->where('cvr_id', $id)->get();
        $pocs = DB::table('cvr_poc as c_p')->selectRaw('id, poc_id, (Select poc_name from poc where id = c_p.poc_id) as poc_name')->where('cvr_id', $id)->get();
        $competitions = DB::table('cvr_competition')->where('cvr_id', $id)->get();
        echo json_encode(array('core' => $core, 'products' => $products, 'poc' => $pocs, 'competitions' => $competitions));
    }

    public function update_cvr(Request $request){
        $insert_core = DB::table('cvr_core')->where('id', $request->cvr_id)->update([
            'report_updated_at' => date('M d Y'),
            'report_updated_by' =>  Auth::user()->id,
            'date_of_visit' => $request->date,
            'customer_visited' => $request->customer_id,
            'location' => $request->location,
            'time_spent' => $request->time_spent,
            'purpose_of_visit' => $request->purpose,
            'opportunity' => $request->opportunity,
            'bussiness_value' => $request->annualBusiness,
            'relationship' => $request->relationship,
            'description' => $request->description
            //'updated_at' =>  date('Y-m-d H:i:s')
            ]);

            DB::table('cvr_poc')->where('cvr_id', $request->cvr_id)->delete();
            foreach($request->poc_list as $poc){
                $insert_poc = DB::table('cvr_poc')->insert([
                    'poc_id' => $poc["poc_id"],
                    'cvr_id' => $request->cvr_id
                ]);
            }

            DB::table('cvr_competition')->where('cvr_id', $request->cvr_id)->delete();
            foreach($request->competition_list as $comp){
                $insert_comp = DB::table('cvr_competition')->insert([
                    'cvr_id' => $request->cvr_id,
                    'name' => $comp["name"],
                    'strength' => $comp['strength']
                ]);
            }

            DB::table('cvr_products')->where('cvr_id', $request->cvr_id)->delete();
            foreach($request->products as $pro){
                $insert_pro = DB::table('cvr_products')->insert([
                    'cvr_id' => $request->cvr_id,
                    'category_id' => $pro
                ]);
            }

            $insert_notification = DB::table('notifications_list')->insert([
                'code' => 102,
                'message' => 'CVR Updated',
                'cvr_id' => $request->cvr_id
            ]);
    
            if($insert_core){
                echo json_encode('success');
            }else{
                echo json_encode('failed');
            }
        //echo json_encode($request->cvr_id);
    }

}
