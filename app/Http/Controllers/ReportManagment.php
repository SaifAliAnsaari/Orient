<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use URL;
use Auth;
use Mail;
use File;
use App\Mail\SendMailable;
use Illuminate\Support\Facades\Storage;

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
        $customers = DB::table('customers')->get();
        $competitions = DB::table('cvr_competition')->groupBy('name')->get();
        $data = DB::table('pick_up_delivery')->get();

        $provinces = [];
        $counter = 0;
        array_map(function($item) use($data, &$provinces, &$counter, &$cities){
            $provinces[$counter]['province'] = $item['province'];
            $provinces[$counter]['id'] = $item['id'];
            $counter ++;
        }, json_decode($data, true));
        
        $filtered_privinces = $this->unique_multidim_array($provinces, "province");

        return view('report_managment.new_cvr', ['categories' => DB::table('sub_categories')->get(), 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'cust' => $customers, 'competitions' => $competitions, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval, 'provinces' => $filtered_privinces, 'data' => $data]);
    }

    //Get Customers AND POC List
    public function GetCustForCVR(){
        echo json_encode(array(
            'customers' => DB::table('customers')->get(),
            'poc'  => ""
        ));
    }

    //Get Customer Address
    public function get_cust_address(Request $request){
        echo json_encode(array('address' => DB::table('customers')->where('id', $request->id)->first(), 'pocs' => DB::table('poc')->where('company_name', $request->id)->get()));
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
                'email' => $request->email
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
       
        // if(Auth::user()->designation == '1' || Auth::user()->designation == '2'){
        //     $insert_core = DB::table('cvr_core')->insertGetId([
        //         'report_created_at' => date('Y-m-d'),
        //         'report_created_by' =>  Auth::user()->id,
        //         'date_of_visit' => $request->date,
        //         'customer_visited' => $request->customer_id,
        //         'location' => $request->location,
        //         'time_spent' => $request->time_spent,
        //         'purpose_of_visit' => $request->purpose,
        //         'opportunity' => $request->opportunity,
        //         'bussiness_value' => $request->annualBusiness,
        //         'relationship' => $request->relationship,
        //         'description' => $request->description,
        //         'is_approved' => 1
        //         ]);
        // }else{
            $insert_core = DB::table('cvr_core')->insertGetId([
                'report_created_at' => date('Y-m-d'),
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
       // }
        
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
                'strength' => $request->competitions_strength
            ]);
        }
        foreach($request->products as $pro){
            $insert_pro = DB::table('cvr_products')->insert([
                'cvr_id' => $insert_core,
                'category_id' => $pro
            ]);
        }

        
        // $insert_notification = DB::table('notifications_list')->insert([
        //     'code' => 102,
        //     'message' => 'New CVR added',
        //     'cvr_id' => $insert_core
        // ]);

        if($insert_core){


            $check_sub_emp = DB::table('subecribed_notif_new')->whereRaw('emp_id = '.Auth::user()->id.' And cvr IS NOT NULL')->first();
            if($check_sub_emp){
                if($check_sub_emp->cvr){
                    $cvrs = explode(",", $check_sub_emp->cvr);

                    //For Email
                    $get_email_addresses = DB::table('users')->whereRaw('id IN (Select emp_id from subscribed_notifications WHERE email = 1 AND notification_code_id = 102)')->get();
                    $cust_name = DB::table('customers')->select('company_name')->where('id', $request->customer_id)->first();
                    $cvr_id = array('id' => $insert_core);
                    $url = '/fpdf?'.http_build_query(json_decode(json_encode($cvr_id), true));
                    $final_url = URL::to('/').$url;
                    $file_name = "cvr".time().".pdf";
                    file_put_contents($file_name, fopen($final_url, 'r'));
                    foreach($cvrs as $cvr){
                        DB::table('notifications_list')->insert([
                            'code' => 102,
                            'message' =>'New CVR added',
                            'cvr_id' => $insert_core,
                            'notif_to' => $cvr
                        ]);
                    }

                    foreach($get_email_addresses as $email){
                        if($email->id == $cvr){
                            $message = '<strong>Dear '.$email->name. '</strong>, <br> <br> A new CVR has been added for the customer: <strong>'.$cust_name->company_name."</strong> by: <strong>".Auth::user()->name."</strong>. <br> <br> Attached is the complete Customer visit report for your reference.";

                            Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "CVR: Added by ".Auth::user()->name, "attachment" => URL::to('/').'/'.$file_name]));
                        }
                    }
                    $path = public_path()."/". $file_name;
                    if(file_exists($path)) {
                        unlink($path);
                    }
                }
            }


            
            // if(!$get_email_addresses->isEmpty()){
            //     foreach($get_email_addresses as $email){

            //         $message = '<strong>Dear '.$email->name. '</strong>, <br> <br> A new CVR has been added for the customer: <strong>'.$cust_name->company_name."</strong> by: <strong>".Auth::user()->name."</strong>. <br> <br> Attached is the complete Customer visit report for your reference.";

            //         Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "CVR: Added by ".Auth::user()->name, "attachment" => URL::to('/').'/'.$file_name]));
            //     }
            // }
            
           

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
        return view('report_managment.cvr_list', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
    }

    public function GetCVRList(Request $request){
        
        $check = DB::table('cvr_core')->get();
       
        if($request->type == '1'){
            if(!$check->isEmpty()){
                foreach($check as $_check){
                    if($_check->report_created_by == Auth::user()->id){
                        echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END)')->get(), 'editable' => 0));
                        die;
                    }else{
                        echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END)')->get(), 'editable' => 1));
                        die;
                    }
                }
            }else{
                echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END)')->get(), 'editable' => 1));
                die;
            }
            
            // die;
            // if(Auth::user()->id == DB::table('cvr_core')->where()){
            //     echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->get(), 'editable' => 1));
               
            // }else{
            //     echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('report_created_by', Auth::user()->id)->get(), 'editable' => 0));
            // }
        }else if($request->type == '2'){
            if(!$check->isEmpty()){
                foreach($check as $_check){
                    if($_check->report_created_by == Auth::user()->id){
                        echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 1')->get(), 'editable' => 0));
                        die;
                    }else{
                        echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 1')->get(), 'editable' => 1));
                        die;
                    }
                }
            }else{
                echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 1')->get(), 'editable' => 1));
                die;
            }
           
            // if(Auth::user()->designation == '1' || Auth::user()->designation == '2'){
            //     echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('is_approved', 1)->get(), 'editable' => 1));
            // }else{
            //     echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('report_created_by = "'.Auth::user()->id.'" AND is_approved = 1')->get(), 'editable' => 0));
            // }
        }else if($request->type == '3'){
            if(!$check->isEmpty()){
                foreach($check as $_check){
                    if($_check->report_created_by == Auth::user()->id){
                        echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 2')->get(), 'editable' => 0));
                        die;
                    }else{
                        echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 2')->get(), 'editable' => 1));
                        die;
                    }
                }
            }else{
                echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 2')->get(), 'editable' => 1));
                die;
            }
            
            // if(Auth::user()->designation == '1' || Auth::user()->designation == '2'){
            //     echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('is_approved', 2)->get(), 'editable' => 1));
            // }else{
            //     echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('report_created_by = "'.Auth::user()->id.'" AND is_approved = 2')->get(), 'editable' => 0));
            // }
        }else if($request->type == '4'){
            if(!$check->isEmpty()){
                foreach($check as $_check){
                    if($_check->report_created_by == Auth::user()->id){
                        echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 0')->get(), 'editable' => 0));
                        die;
                    }else{
                        echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 0')->get(), 'editable' => 1));
                        die;
                    }
                }
            }else{
                echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select cvr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 0')->get(), 'editable' => 1));
                die;
            }
            
            // if(Auth::user()->designation == '1' || Auth::user()->designation == '2'){
            //     echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('is_approved', 0)->get(), 'editable' => 1));
            // }else{
            //     echo json_encode(array('info' => DB::table('cvr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('report_created_by = "'.Auth::user()->id.'" AND is_approved = 0')->get(), 'editable' => 0));
            // }
        }
        
        
    }


    //CVR Preview
    public function cvr_preview($id){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $core = DB::table('cvr_core as cc')->selectRaw('id, report_created_at, report_created_by, date_of_visit, customer_visited, location, time_spent, purpose_of_visit, opportunity, bussiness_value, relationship, description, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('id', $id)->first();
        if($core){
            $products = DB::table('cvr_products as cp')->selectRaw('id, category_id, (Select name from sub_categories where id = cp.category_id) as cat_name')->where('cvr_id', $id)->get();
            $pocs = DB::table('cvr_poc as c_p')->selectRaw('id, poc_id, (Select poc_name from poc where id = c_p.poc_id) as poc_name')->where('cvr_id', $id)->get();
            $competitions = DB::table('cvr_competition')->where('cvr_id', $id)->get();

            $data = array('core' => $core, 'products' => $products, 'pocs' => $pocs, 'competitions' => $competitions);

            $sub_cvr = DB::table('subecribed_notif_new')->whereRaw(Auth::user()->id.' IN (cvr) and emp_id = '.$core->report_created_by)->first();
            if(Auth::user()->id == $core->report_created_by){
                return view('report_managment.cvr_preview', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'core' => $core, 'products' => $products, 'poc' => $pocs, 'competition' => $competitions, 'id' => $id, 'testData' => $data, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval, 'approval_able' => 0]);
            }else if($sub_cvr){
                return view('report_managment.cvr_preview', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'core' => $core, 'products' => $products, 'poc' => $pocs, 'competition' => $competitions, 'id' => $id, 'testData' => $data, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval, 'approval_able' => 1]);
            }else{
                return redirect('/');
            }
            
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
        $customers = DB::table('customers')->get();
        $competitions = DB::table('cvr_competition')->get();
        if($core){
            if(Auth::user()->designation == '1' || Auth::user()->designation == '2'){
                if($core){
                    return view('report_managment.edit_cvr', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'categories' => DB::table('sub_categories')->get(), 'id' => $id, 'competitions' => $competitions, 'unread_notif' => $this->unread_notif_approval, 'approval_notif' => $this->approval_notif, 'cust' => $customers]);
                }else{
                    return redirect('/');
                }
            }else{
                if($core->report_created_by == Auth::user()->id && $core->is_approved == 2){
                    if($core){
                        return view('report_managment.edit_cvr', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'categories' => DB::table('sub_categories')->get(), 'id' => $id, 'competitions' => $competitions, 'unread_notif' => $this->unread_notif_approval, 'approval_notif' => $this->approval_notif, 'cust' => $customers]);
                    }else{
                        return redirect('/');
                    }
                }else{
                    return redirect('/');
                }
            }
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
        try{
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
                    'strength' => $request->competitions_strength
                ]);
            }

            DB::table('cvr_products')->where('cvr_id', $request->cvr_id)->delete();
            foreach($request->products as $pro){
                $insert_pro = DB::table('cvr_products')->insert([
                    'cvr_id' => $request->cvr_id,
                    'category_id' => $pro
                ]);
            }

            $check_sub_emp = DB::table('subecribed_notif_new')->whereRaw('emp_id = '.Auth::user()->id.' And cvr IS NOT NULL')->first();
            if($check_sub_emp){
                if($check_sub_emp->cvr){
                    $cvrs = explode(",", $check_sub_emp->cvr);

                    //For Email
                    $get_email_addresses = DB::table('users')->whereRaw('id IN (Select emp_id from subscribed_notifications WHERE email = 1 AND notification_code_id = 102)')->get();
                    $cust_name = DB::table('customers')->select('company_name')->where('id', $request->customer_id)->first();
                    $cvr_id = array('id' => $request->cvr_id);
                    $url = '/fpdf?'.http_build_query(json_decode(json_encode($cvr_id), true));
                    $final_url = URL::to('/').$url;
                    $file_name = "cvr".time().".pdf";
                    file_put_contents($file_name, fopen($final_url, 'r'));
                    foreach($cvrs as $cvr){
                        DB::table('notifications_list')->insert([
                            'code' => 102,
                            'message' =>'CVR Updated',
                            'cvr_id' => $request->cvr_id,
                            'notif_to' => $cvr
                        ]);
                    }
                    foreach($get_email_addresses as $email){
                        if($email->id == $cvr){
                            $message = '<strong>Dear '.$email->name. '</strong>, <br> <br> A CVR has been updated for the customer: <strong>'.$cust_name->company_name."</strong> by: <strong>".Auth::user()->name."</strong>. <br> <br> Attached is the complete Customer visit report for your reference.";
                            //Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "CVR Added"]));
                            Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "CVR Updated by ".Auth::user()->name, "attachment" => URL::to('/').'/'.$file_name]));
                        }
                    }
                    $path = public_path()."/". $file_name;
                    if(file_exists($path)) {
                        unlink($path);
                    }
                }
            }
            // $insert_notification = DB::table('notifications_list')->insert([
            //     'code' => 102,
            //     'message' => 'CVR Updated',
            //     'cvr_id' => $request->cvr_id
            // ]);
    
            
            // if(!$get_email_addresses->isEmpty()){
            //     foreach($get_email_addresses as $email){
            //         $message = '<strong>Dear '.$email->name. '</strong>, <br> <br> A CVR has been updated for the customer: <strong>'.$cust_name->company_name."</strong> by: <strong>".Auth::user()->name."</strong>. <br> <br> Attached is the complete Customer visit report for your reference.";
            //         //Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "CVR Added"]));
            //         Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "CVR Updated by ".Auth::user()->name, "attachment" => URL::to('/').'/'.$file_name]));
            //     }
            // }
           
           
            echo json_encode('success');
            
        }catch(\Illuminate\Database\QueryException $ex){ 
            echo json_encode('failed'); 
        }
    }


    //Download CVR PDF
    public function download_pdf($id){

        $cvr_id = array('id' => $id);

        $url = '/fpdf?'.http_build_query(json_decode(json_encode($cvr_id), true));
        if($url){
            return redirect($url);
        }
           
        // $core = DB::table('cvr_core as cc')->selectRaw('id, report_created_at, report_created_by, date_of_visit, customer_visited, location, time_spent, purpose_of_visit, opportunity, bussiness_value, relationship, description, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('id', $id)->first();
        // if($core){
        //     $products = DB::table('cvr_products as cp')->selectRaw('id, category_id, (Select name from sub_categories where id = cp.category_id) as cat_name')->where('cvr_id', $id)->get();
        //     $pocs = DB::table('cvr_poc as c_p')->selectRaw('id, poc_id, (Select poc_name from poc where id = c_p.poc_id) as poc_name')->where('cvr_id', $id)->get();
        //     $competitions = DB::table('cvr_competition')->where('cvr_id', $id)->get();

        //     $data = [];
        //     $data = array('core' => $core, 'products' => $products, 'pocs' => $pocs, 'competitions' => $competitions);
            
        //     //$url = '/fpdf?'.http_build_query(json_decode(json_encode($data), true));
            
        //     $cvr_id = array('id' => $id);

        //     $url = '/fpdf?'.http_build_query(json_decode(json_encode($cvr_id), true));
        //     if($url){
        //         return redirect($url);
        //     }
        // }else{
        //     return redirect('/');
        // }
    } 


    //Send Mail
    public function send_mail($id){

        // $unique_token = $this->random_string(100);
        // //echo URL::to('/').'/'."send_mail/".$unique_token; die;
        // //$test;
        // if(DB::table('cvr_mail_key')->where('token' , $unique_token)->first()){
        //     header("Refresh:0");
        // }else{
        //     $insert_token = DB::table('cvr_mail_key')->insert([
        //         'token' => $unique_token,
        //         'cvr_id' => $id,
        //         'created_at' => date('Y-m-d H:i:s')
        //     ]);
        //     $message = URL::to('/').'/'."save_cvr_pdf/".$unique_token;
        //     // $client_email = DB::table('customers')->where('id', $id)->first();
        //     Mail::to(Auth::user()->email)->send(new SendMailable(["message" => $message, "subject" => "CVR "]));
        //     return redirect('cvr_preview/'.$id);
        // }

        $cvr_id = array('id' => $id);

        $url = '/fpdf?'.http_build_query(json_decode(json_encode($cvr_id), true));
        $email_address = Auth::user()->email;
        $final_url = URL::to('/').$url;

        $file_name = "cvr".time().".pdf";
        file_put_contents($file_name, fopen($final_url, 'r'));

        $message = 'CVR Attachment';
        Mail::to(Auth::user()->email)->send(new SendMailable(["message" => $message, "subject" => "CVR", "attachment" => URL::to('/').'/'.$file_name]));

        // if(is_file(public_path($file_name))){
        //     File::delete($file_name);
        // }
        $path = public_path()."/". $file_name;
        if(file_exists($path)) {
            unlink($path);
        }
        
        return redirect('cvr_preview/'.$id);
        
        // die;

        // $core = DB::table('cvr_core as cc')->selectRaw('id, report_created_at, report_created_by, date_of_visit, customer_visited, location, time_spent, purpose_of_visit, opportunity, bussiness_value, relationship, description, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('id', $id)->first();
        // if($core){
        //     $products = DB::table('cvr_products as cp')->selectRaw('id, category_id, (Select name from sub_categories where id = cp.category_id) as cat_name')->where('cvr_id', $id)->get();
        //     $pocs = DB::table('cvr_poc as c_p')->selectRaw('id, poc_id, (Select poc_name from poc where id = c_p.poc_id) as poc_name')->where('cvr_id', $id)->get();
        //     $competitions = DB::table('cvr_competition')->where('cvr_id', $id)->get();
            
        //     $data = [];
        //     $data = array('core' => $core, 'products' => $products, 'pocs' => $pocs, 'competitions' => $competitions);
            
            // $url = '/fpdf?'.http_build_query(json_decode(json_encode($data), true));

            // $email_address = Auth::user()->email;
            // $final_url = URL::to('/').$url;

            // $file_name = "cvr".time().".pdf";
            // file_put_contents($file_name, fopen($final_url, 'r'));

            // $message = 'CVR Attachment';
            // Mail::to(Auth::user()->email)->send(new SendMailable(["message" => $message, "subject" => "CVR", "attachment" => URL::to('/').'/'.$file_name]));

            // if(is_file(public_path($file_name))){
            //     File::delete($file_name);
            // }
            
            // return redirect('cvr_preview/'.$id);
        // }else{
        //     return redirect('/');
        // }
    }


    //Save CVR Approval
    public function save_cvr_approval(Request $request){
        
        $data = DB::table('cvr_core')->where('id', $request->id)->first();
        $insert = DB::table('cvr_approval')->insert([
            'cvr_id' => $request->id,
            'approval' => $request->approval,
            'remarks' => $request->remarks,
            'approval_by' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        if($insert){
            if($request->approval == '1'){
                DB::table('cvr_core')->where('id', $request->id)->update(['is_approved' => 1]);
                DB::table('notifications_list')->insert([
                    'code' => 103,
                    'message' =>'CVR has been approved',
                    'cvr_id' => $request->id,
                    'notif_to' => $data->report_created_by
                ]);
                // DB::table('approval_notif')->insert([
                //     'cvr_id' => $request->id,
                //     'notif_to' => DB::raw('(Select report_created_by from cvr_core where id = "'.$request->id.'")'),
                //     'approval_by' => Auth::user()->id,
                //     'approval' => 1,
                //     'remarks' => $request->remarks,
                //     'created_at' => date('Y-m-d H:i:s')
                // ]);
                if(DB::table('subscribed_notifications')->where('notification_code_id = 103 AND email = 1 AND emp_id = (Select report_created_by from cvr_core where id = "'.$request->id.'")')){
                    $get_email_addresses = DB::table('users')->select('email', 'name')->whereRaw('id = (Select report_created_by from cvr_core where id = "'.$request->id.'")')->first();
                    $cust_name = DB::table('customers')->select('company_name')->whereRaw('id = (Select customer_visited from cvr_core where id = "'.$request->id.'")')->first();
                    if($get_email_addresses){
    
                        $message = '<strong>Dear '.$get_email_addresses->name. '</strong>, <br> <br> Your CVR against the customer: <strong>'.$cust_name->company_name."</strong> has been approved by your line manager : <strong>".Auth::user()->name."</strong>.";

                        Mail::to($get_email_addresses->email)->send(new SendMailable(["message" => $message, "subject" => "CVR Approved: CVR for ".$cust_name->company_name]));
                        
                    }
                }

               
            }else{
                DB::table('cvr_core')->where('id', $request->id)->update(['is_approved' => 2]);
                DB::table('notifications_list')->insert([
                    'code' => 103,
                    'message' =>'CVR has been disapproved',
                    'cvr_id' => $request->id,
                    'notif_to' => $data->report_created_by
                ]);
                // DB::table('approval_notif')->insert([
                //     'cvr_id' => $request->id,
                //     'notif_to' => DB::raw('(Select report_created_by from cvr_core where id = "'.$request->id.'")'),
                //     'approval_by' => Auth::user()->id,
                //     'approval' => 2,
                //     'remarks' => $request->remarks,
                //     'created_at' => date('Y-m-d H:i:s')
                // ]);
                if(DB::table('subscribed_notifications')->where('notification_code_id = 103 AND email = 1 AND emp_id = (Select      report_created_by from cvr_core where id = "'.$request->id.'")')){
                    $get_email_addresses = DB::table('users')->select('email', 'name')->whereRaw('id = (Select report_created_by from cvr_core where id = "'.$request->id.'")')->first();
                    $cust_name = DB::table('customers')->select('company_name')->whereRaw('id = (Select customer_visited from cvr_core where id = "'.$request->id.'")')->first();
                    if($get_email_addresses){
    
                        $message = '<strong>Dear '.$get_email_addresses->name. '</strong>, <br> <br> Your CVR against the customer: <strong>'.$cust_name->company_name."</strong> has been disapproved by your line manager: <strong>".Auth::user()->name."</strong>, with the following remarks: <br> <br>".$request->remarks;

                        Mail::to($get_email_addresses->email)->send(new SendMailable(["message" => $message, "subject" => "CVR Disapproved: CVR for ".$cust_name->company_name]));
                        
                    }
                }
               
            }
            
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
    }


    //Disapproved CVR Detail
    public function disapproved_detail($id){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        if(DB::table('cvr_core')->whereRaw('id = "'.$id.'" AND is_approved = 2 AND report_created_by = '.Auth::user()->id)->first()){
            $data = DB::table('cvr_approval as ca')->selectRaw('remarks, cvr_id, Date(created_at) as date, (Select name from users where id = ca.approval_by) as approval_by')->where('cvr_id', $id)->orderBy('id','DESC')->first();
            //echo '<pre>'; print_r($data); die;
            return view('report_managment.disapproved_cvr', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval, 'data' => $data]);
        }else{
            return redirect('/');
        }
    }







    //SVR
    public function new_svr(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $customers = DB::table('customers')->get();
        $competitions = DB::table('cvr_competition')->groupBy('name')->get();
        $data = DB::table('pick_up_delivery')->get();

        $provinces = [];
        $counter = 0;
        array_map(function($item) use($data, &$provinces, &$counter, &$cities){
            $provinces[$counter]['province'] = $item['province'];
            $provinces[$counter]['id'] = $item['id'];
            $counter ++;
        }, json_decode($data, true));
        
        $filtered_privinces = $this->unique_multidim_array($provinces, "province");

        return view('report_managment.new_svr', ['categories' => DB::table('sub_categories')->get(), 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'cust' => $customers, 'competitions' => $competitions, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval, 'provinces' => $filtered_privinces, 'data' => $data]);
    }

    //Save SVR
    public function save_svr(Request $request){
        // if(Auth::user()->designation == '1' || Auth::user()->designation == '2'){
        //     $insert_core = DB::table('svr_core')->insertGetId([
        //         'report_created_at' => date('Y-m-d'),
        //         'report_created_by' =>  Auth::user()->id,
        //         'date_of_visit' => $request->date,
        //         'customer_visited' => $request->customer_id,
        //         'location' => $request->location,
        //         'time_spent' => $request->time_spent,
        //         'purpose_of_visit' => $request->purpose,
        //         'relationship' => $request->relationship,
        //         'description' => $request->description,
        //         'is_approved' => 1
        //         ]);
        // }else{
            $insert_core = DB::table('svr_core')->insertGetId([
                'report_created_at' => date('Y-m-d'),
                'report_created_by' =>  Auth::user()->id,
                'date_of_visit' => $request->date,
                'customer_visited' => $request->customer_id,
                'location' => $request->location,
                'time_spent' => $request->time_spent,
                'purpose_of_visit' => $request->purpose,
                'relationship' => $request->relationship,
                'description' => $request->description
                //'created_at' =>  date('Y-m-d H:i:s')
                ]);
        //}
        
        foreach($request->poc_list as $poc){
            $insert_poc = DB::table('svr_poc')->insert([
                'poc_id' => $poc["poc_id"],
                'svr_id' => $insert_core
            ]);
        }
        foreach($request->competition_list as $comp){
            $insert_comp = DB::table('cvr_competition')->insert([
                'svr_id' => $insert_core,
                'name' => $comp["name"],
                'strength' => $request->competitions_strength
            ]);
        }
        foreach($request->products as $pro){
            $insert_pro = DB::table('svr_products')->insert([
                'svr_id' => $insert_core,
                'category_id' => $pro
            ]);
        }

        // $insert_notification = DB::table('notifications_list')->insert([
        //     'code' => 106,
        //     'message' => 'New SVR added',
        //     'svr_id' => $insert_core
        // ]);
        $check_sub_emp = DB::table('subecribed_notif_new')->whereRaw('emp_id = '.Auth::user()->id.' And svr IS NOT NULL')->first();
        if($check_sub_emp){
            if($check_sub_emp->svr){
                $svrs = explode(",", $check_sub_emp->svr);

                //For Email
                $get_email_addresses = DB::table('users')->whereRaw('id IN (Select emp_id from subscribed_notifications WHERE email = 1 AND notification_code_id = 106)')->get();
                $cust_name = DB::table('customers')->select('company_name')->where('id', $request->customer_id)->first();
                
                $svr_id = array('id' => $insert_core);
                $url = '/fpdf/svr.php?'.http_build_query(json_decode(json_encode($svr_id), true));
                $final_url = URL::to('/').$url;
                $file_name = "svr".time().".pdf";
                file_put_contents($file_name, fopen($final_url, 'r'));
                foreach($svrs as $svr){
                    DB::table('notifications_list')->insert([
                        'code' => 106,
                        'message' =>'New SVR added',
                        'svr_id' => $insert_core,
                        'notif_to' => $svr
                    ]);
                }
                foreach($get_email_addresses as $email){
                    if($email->id == $svr){
                        $message = 'Dear <strong> '.$email->name. '</strong>, <br> <br> A new SVR has been added for the customer: <strong>'.$cust_name->company_name."</strong> by: <strong>".Auth::user()->name."</strong>. <br> <br> Attached is the complete Service visit report for your reference.";


                        Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "SVR: Added by ".Auth::user()->name, "attachment" => URL::to('/').'/'.$file_name]));
                    }
                }
                $path = public_path()."/". $file_name;
                if(file_exists($path)) {
                    unlink($path);
                }
            }
        }

        if($insert_core){
           
            // if(!$get_email_addresses->isEmpty()){
            //     foreach($get_email_addresses as $email){

            //         $message = 'Dear <strong> '.$email->name. '</strong>, <br> <br> A new SVR has been added for the customer: <strong>'.$cust_name->company_name."</strong> by: <strong>".Auth::user()->name."</strong>. <br> <br> Attached is the complete Service visit report for your reference.";


            //         Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "SVR: Added by ".Auth::user()->name, "attachment" => URL::to('/').'/'.$file_name]));
            //     }
            // }
           

            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
    }

    //SVR List
    public function svr_list(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        return view('report_managment.svr_list', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
    }

    //Get SVR List
    public function GetSVRList(Request $request){
        
        $check = DB::table('svr_core')->get();
        if($request->type == '1'){
            if(!$check->isEmpty()){
                foreach($check as $_check){
                    if($_check->report_created_by == Auth::user()->id){
                        echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END)')->get(), 'editable' => 0));
                        die;
                    }else{
                        echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END)')->get(), 'editable' => 1));
                        die;
                    }
                }
            }else{
                echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END)')->get(), 'editable' => 1));
                die;
            }
            
            // if(Auth::user()->designation == '1' || Auth::user()->designation == '2'){
            //     echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->get(), 'editable' => 1));
            // }else{
            //     echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('report_created_by', Auth::user()->id)->get(), 'editable' => 0));
            // }
        }else if($request->type == '2'){
            if(!$check->isEmpty()){
                foreach($check as $_check){
                    if($_check->report_created_by == Auth::user()->id){
                        echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 1')->get(), 'editable' => 0));
                        die;
                    }else{
                        echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 1')->get(), 'editable' => 1));
                        die;
                    }
                }
            }else{
                echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 1')->get(), 'editable' => 1));
                die;
            }
           
            // if(Auth::user()->designation == '1' || Auth::user()->designation == '2'){
            //     echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('is_approved', 1)->get(), 'editable' => 1));
            // }else{
            //     echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('report_created_by = "'.Auth::user()->id.'" AND is_approved = 1')->get(), 'editable' => 0));
            // }
        }else if($request->type == '3'){
             if(!$check->isEmpty()){
                foreach($check as $_check){
                    if($_check->report_created_by == Auth::user()->id){
                        echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 2')->get(), 'editable' => 0));
                        die;
                    }else{
                        echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 2')->get(), 'editable' => 1));
                        die;
                    }
                }
            }else{
                echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 2')->get(), 'editable' => 1));
                die;
            }
           
            // if(Auth::user()->designation == '1' || Auth::user()->designation == '2'){
            //     echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('is_approved', 2)->get(), 'editable' => 1));
            // }else{
            //     echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('report_created_by = "'.Auth::user()->id.'" AND is_approved = 2')->get(), 'editable' => 0));
            // }
        }else if($request->type == '4'){
             if(!$check->isEmpty()){
                foreach($check as $_check){
                    if($_check->report_created_by == Auth::user()->id){
                        echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 0')->get(), 'editable' => 0));
                        die;
                    }else{
                        echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 0')->get(), 'editable' => 1));
                        die;
                    }
                }
            }else{
                echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('(Case When report_created_by = '.Auth::user()->id.' Then report_created_by = '.Auth::user()->id.' Else '.Auth::user()->id.' IN (Select svr from subecribed_notif_new where emp_id = cc.report_created_by) END) AND is_approved = 0')->get(), 'editable' => 1)); 
                die;
            }
           
            // if(Auth::user()->designation == '1' || Auth::user()->designation == '2'){
            //     echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('is_approved', 0)->get(), 'editable' => 1));
            // }else{
            //     echo json_encode(array('info' => DB::table('svr_core as cc')->selectRaw('id, report_created_by, customer_visited, report_created_at, date_of_visit, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->whereRaw('report_created_by = "'.Auth::user()->id.'" AND is_approved = 0')->get(), 'editable' => 0));
            // }
        }
    }

    //Edit SVR
    public function edit_svr($id){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $core = DB::table('svr_core as cc')->where('id', $id)->first();
        $customers = DB::table('customers')->get();
        $competitions = DB::table('cvr_competition')->get();
        if($core){
            if(Auth::user()->designation == '1' || Auth::user()->designation == '2'){
                if($core){
                    return view('report_managment.edit_svr', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'categories' => DB::table('sub_categories')->get(), 'id' => $id, 'competitions' => $competitions, 'unread_notif' => $this->unread_notif_approval, 'approval_notif' => $this->approval_notif, 'cust' => $customers]);
                }else{
                    return redirect('/');
                }
            }else{
                if($core->report_created_by == Auth::user()->id && $core->is_approved == 2){
                    if($core){
                        return view('report_managment.edit_svr', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'categories' => DB::table('sub_categories')->get(), 'id' => $id, 'competitions' => $competitions, 'unread_notif' => $this->unread_notif_approval, 'approval_notif' => $this->approval_notif, 'cust' => $customers]);
                    }else{
                        return redirect('/');
                    }
                }else{
                    return redirect('/');
                }
            }
        }else{
            return redirect('/');
        }
        
    }

    //Get Current SVR Data
    public function GetCurrentSvr($id){
        $core = DB::table('svr_core as cc')->selectRaw('id, report_created_at, report_created_by, date_of_visit, customer_visited, location, time_spent, purpose_of_visit, relationship, description, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('id', $id)->first();
        $products = DB::table('svr_products as cp')->selectRaw('id, category_id, (Select name from sub_categories where id = cp.category_id) as cat_name')->where('svr_id', $id)->get();
        $pocs = DB::table('svr_poc as c_p')->selectRaw('id, poc_id, (Select poc_name from poc where id = c_p.poc_id) as poc_name')->where('svr_id', $id)->get();
        $competitions = DB::table('cvr_competition')->where('svr_id', $id)->get();
        echo json_encode(array('core' => $core, 'products' => $products, 'poc' => $pocs, 'competitions' => $competitions));
    }

    //Update SVR
    public function update_svr(Request $request){
       
        try{
            $insert_core = DB::table('svr_core')->where('id', $request->svr_id)->update([
                'report_updated_at' => date('M d Y'),
                'report_updated_by' =>  Auth::user()->id,
                'date_of_visit' => $request->date,
                'customer_visited' => $request->customer_id,
                'location' => $request->location,
                'time_spent' => $request->time_spent,
                'purpose_of_visit' => $request->purpose,
                'relationship' => $request->relationship,
                'description' => $request->description
                //'updated_at' =>  date('Y-m-d H:i:s')
                ]);
    
                DB::table('svr_poc')->where('svr_id', $request->svr_id)->delete();
                foreach($request->poc_list as $poc){
                    $insert_poc = DB::table('svr_poc')->insert([
                        'poc_id' => $poc["poc_id"],
                        'svr_id' => $request->svr_id
                    ]);
                }
    
                DB::table('cvr_competition')->where('svr_id', $request->svr_id)->delete();
                foreach($request->competition_list as $comp){
                    $insert_comp = DB::table('cvr_competition')->insert([
                        'svr_id' => $request->svr_id,
                        'name' => $comp["name"],
                        'strength' => $request->competitions_strength
                    ]);
                }
    
                DB::table('svr_products')->where('svr_id', $request->svr_id)->delete();
                foreach($request->products as $pro){
                    $insert_pro = DB::table('svr_products')->insert([
                        'svr_id' => $request->svr_id,
                        'category_id' => $pro
                    ]);
                }
    
                // $insert_notification = DB::table('notifications_list')->insert([
                //     'code' => 106,
                //     'message' => 'SVR Updated',
                //     'svr_id' => $request->svr_id
                // ]);
                $check_sub_emp = DB::table('subecribed_notif_new')->whereRaw('emp_id = '.Auth::user()->id.' And svr IS NOT NULL')->first();
                if($check_sub_emp){
                    if($check_sub_emp->svr){
                        $svrs = explode(",", $check_sub_emp->svr);

                        //For Email
                        $get_email_addresses = DB::table('users')->whereRaw('id IN (Select emp_id from subscribed_notifications WHERE email = 1 AND notification_code_id = 106)')->get();
                        $cust_name = DB::table('customers')->select('company_name')->where('id', $request->customer_id)->first();
                        $svr_id = array('id' => $request->svr_id);
                        $url = '/fpdf/svr.php?'.http_build_query(json_decode(json_encode($svr_id), true));
                        $final_url = URL::to('/').$url;
                        $file_name = "cvr".time().".pdf";
                        file_put_contents($file_name, fopen($final_url, 'r'));
                        foreach($svrs as $svr){
                            DB::table('notifications_list')->insert([
                                'code' => 106,
                                'message' =>'SVR Updated',
                                'svr_id' => $request->svr_id,
                                'notif_to' => $svr
                            ]);
                        }

                        foreach($get_email_addresses as $email){
                            if($email->id == $svr){
                                $message = 'Dear <strong>'.$email->name. '</strong>, <br> <br> A SVR has been updated for the customer: <strong>'.$cust_name->company_name."</strong> by: <strong>".Auth::user()->name."</strong>. <br> <br> Attached is the complete Service visit report for your reference.";
                                //Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "CVR Added"]));
                                Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "SVR Updated by ".Auth::user()->name, "attachment" => URL::to('/').'/'.$file_name]));
                            }
                        }
                        $path = public_path()."/". $file_name;
                        if(file_exists($path)) {
                            unlink($path);
                        }
                    }
                }
        
                
                // if(!$get_email_addresses->isEmpty()){
                //     foreach($get_email_addresses as $email){
                //         $message = 'Dear <strong>'.$email->name. '</strong>, <br> <br> A SVR has been updated for the customer: <strong>'.$cust_name->company_name."</strong> by: <strong>".Auth::user()->name."</strong>. <br> <br> Attached is the complete Service visit report for your reference.";
                //         //Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "CVR Added"]));
                //         Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "SVR Updated by ".Auth::user()->name, "attachment" => URL::to('/').'/'.$file_name]));
                //     }
                // }
               
    
                echo json_encode('success');
                
            }catch(\Illuminate\Database\QueryException $ex){ 
                echo json_encode('failed'); 
            }
    }

    //SVR Preview
    public function svr_preview($id){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $core = DB::table('svr_core as cc')->selectRaw('id, report_created_at, report_created_by, date_of_visit, customer_visited, location, time_spent, purpose_of_visit, relationship, description, is_approved, (Select name from users where id = cc.report_created_by) as created_by, (Select company_name from customers where id = cc.customer_visited) as customer_name')->where('id', $id)->first();
        if($core){
            $products = DB::table('svr_products as cp')->selectRaw('id, category_id, (Select name from sub_categories where id = cp.category_id) as cat_name')->where('svr_id', $id)->get();
            $pocs = DB::table('svr_poc as c_p')->selectRaw('id, poc_id, (Select poc_name from poc where id = c_p.poc_id) as poc_name')->where('svr_id', $id)->get();
            $competitions = DB::table('cvr_competition')->where('svr_id', $id)->get();

            $data = array('core' => $core, 'products' => $products, 'pocs' => $pocs, 'competitions' => $competitions);

            return view('report_managment.svr_preview', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'core' => $core, 'products' => $products, 'poc' => $pocs, 'competition' => $competitions, 'id' => $id, 'testData' => $data, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
        }else{
            return redirect('/');
        }
    }

    //Save SVR Approval
    public function save_svr_approval(Request $request){
        $data = DB::table('svr_core')->where('id', $request->id)->first();
        $insert = DB::table('svr_approval')->insert([
            'svr_id' => $request->id,
            'approval' => $request->approval,
            'remarks' => $request->remarks,
            'approval_by' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        if($insert){
            if($request->approval == '1'){
                DB::table('svr_core')->where('id', $request->id)->update(['is_approved' => 1]);
                DB::table('notifications_list')->insert([
                    'code' => 107,
                    'message' =>'SVR has been approved',
                    'svr_id' => $request->id,
                    'notif_to' => $data->report_created_by
                ]);
                if(DB::table('subscribed_notifications')->where('notification_code_id = 107 AND email = 1 AND emp_id = (Select report_created_by from svr_core where id = "'.$request->id.'")')){
                    $get_email_addresses = DB::table('users')->select('email', 'name')->whereRaw('id = (Select report_created_by from svr_core where id = "'.$request->id.'")')->first();
                    $cust_name = DB::table('customers')->select('company_name')->whereRaw('id = (Select customer_visited from svr_core where id = "'.$request->id.'")')->first();
                    if($get_email_addresses){
    
                        $message = '<strong>Dear '.$get_email_addresses->name. '</strong>, <br> <br> Your SVR against the customer: <strong>'.$cust_name->company_name."</strong> has been approved by your line manager : <strong>".Auth::user()->name."</strong>.";

                        Mail::to($get_email_addresses->email)->send(new SendMailable(["message" => $message, "subject" => "SVR Approved: SVR for ".$cust_name->company_name]));
                        
                    }
                }
            }else{
                DB::table('svr_core')->where('id', $request->id)->update(['is_approved' => 2]);
                DB::table('notifications_list')->insert([
                    'code' => 107,
                    'message' =>'SVR has been disapproved',
                    'svr_id' => $request->id,
                    'notif_to' => $data->report_created_by
                ]);
                if(DB::table('subscribed_notifications')->where('notification_code_id = 107 AND email = 1 AND emp_id = (Select report_created_by from svr_core where id = "'.$request->id.'")')){
                    $get_email_addresses = DB::table('users')->select('email', 'name')->whereRaw('id = (Select report_created_by from svr_core where id = "'.$request->id.'")')->first();
                    $cust_name = DB::table('customers')->select('company_name')->whereRaw('id = (Select customer_visited from svr_core where id = "'.$request->id.'")')->first();
                    if($get_email_addresses){
    
                        $message = '<strong>Dear '.$get_email_addresses->name. '</strong>, <br> <br> Your SVR against the customer: <strong>'.$cust_name->company_name."</strong> has been disapproved by your line manager : <strong>".Auth::user()->name."</strong>, with the following remarks: <br> <br>".$request->remarks;;

                        Mail::to($get_email_addresses->email)->send(new SendMailable(["message" => $message, "subject" => "SVR Approved: SVR for ".$cust_name->company_name]));
                        
                    }
                }
            }
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
    }

    //Disapproved SVR Detail Page
    public function disapproved_svr_detail($id){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        if(DB::table('svr_core')->whereRaw('id = "'.$id.'" AND is_approved = 2 AND report_created_by = '.Auth::user()->id)->first()){
            $data = DB::table('svr_approval as ca')->selectRaw('remarks, svr_id, Date(created_at) as date, (Select name from users where id = ca.approval_by) as approval_by')->where('svr_id', $id)->orderBy('id','DESC')->first();
            //echo '<pre>'; print_r($data); die;
            return view('report_managment.disapproved_svr', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval, 'data' => $data]);
        }else{
            return redirect('/');
        }
    }

    //Save SVR Mail
    public function send_svr_mail($id){
        $svr_id = array('id' => $id);

        $url = '/fpdf/svr.php?'.http_build_query(json_decode(json_encode($svr_id), true));
        $email_address = Auth::user()->email;
        $final_url = URL::to('/').$url;

        $file_name = "svr".time().".pdf";
        file_put_contents($file_name, fopen($final_url, 'r'));

        $message = 'SVR Attachment';
        Mail::to(Auth::user()->email)->send(new SendMailable(["message" => $message, "subject" => "SVR", "attachment" => URL::to('/').'/'.$file_name]));

        $path = public_path()."/". $file_name;
        if(file_exists($path)) {
            unlink($path);
        }
        
        return redirect('cvr_preview/'.$id);
    }

    //Download SVR PDF
    public function download_svr_pdf($id){
        $svr_id = array('id' => $id);

        $url = '/fpdf/svr.php?'.http_build_query(json_decode(json_encode($svr_id), true));
        if($url){
            return redirect($url);
        }
    }


    function unique_multidim_array($array, $key) { 
        $temp_array = array(); 
        $i = 0; 
        $key_array = array(); 
        
        foreach($array as $val) { 
            if (!in_array($val[$key], $key_array)) { 
                $key_array[$i] = $val[$key]; 
                $temp_array[$i] = $val; 
            } 
            $i++; 
        } 
        return $temp_array; 
    } 

}
