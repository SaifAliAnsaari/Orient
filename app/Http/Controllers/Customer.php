<?php

namespace App\Http\Controllers;

use App\Customer as Cust;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use DB;
use URL;
use Auth;
use Mail;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Mail\SendMailable;

class Customer extends ParentController
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

     public function CustomerView(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $parent_comp = DB::table('customers')->get();
        $data = DB::table('pick_up_delivery')->get();

        $provinces = [];
        $counter = 0;
        array_map(function($item) use($data, &$provinces, &$counter, &$cities){
            $provinces[$counter]['province'] = $item['province'];
            $provinces[$counter]['id'] = $item['id'];
            $counter ++;
        }, json_decode($data, true));
        
        $filtered_privinces = $this->unique_multidim_array($provinces, "province");

        $industries = DB::table('industry')->get();

        // echo '<pre>'; print_r($cities); die;
        return view('customer.list', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'parent_comp' => $parent_comp, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval, 'provinces' => $filtered_privinces, 'data' => $data, 'industries' => $industries]);
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

    //Ajax Call from list-customers.js
    public function CustomersList(Request $request){
        echo json_encode(DB::table('customers as cust')->selectRaw('id, company_name, is_active, address, country, (Select city_name from pick_up_delivery where id = cust.city) as city, (Select name from parent_companies where id = cust.parent_company) as parent_company')->get());
    }

    public function activate_customer(Request $request){
        try{
            $add = DB::table('customers')
            ->where('id', $request->id)->update(
                ['is_active' => 1
                ]);
            if($add){
                echo json_encode('success'); 
            }else{
                echo json_encode('failed'); 
            }

        }catch(\Illuminate\Database\QueryException $ex){ 
            echo json_encode('failed'); 
        }
    }
    
    public function deactivate_customer(Request $request){
        try{
            $add = DB::table('customers')
            ->where('id', $request->id)->update(
                ['is_active' => 0
                ]);
            if($add){
                echo json_encode('success'); 
            }else{
                echo json_encode('failed'); 
            }
            
        }catch(\Illuminate\Database\QueryException $ex){ 
            echo json_encode('failed'); 
        }
    }

    public function delete_cust($id){
        $delete = DB::table('customers')->where('id', $id)->delete();
        if($delete){
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
    }

    public function save_parent_company(Request $request){
        if(DB::table('parent_companies')->where('name', $request->name)->first()){
            echo json_encode('already_exist');
        }else{
            $insert = DB::table('parent_companies')->insertGetId([
                'name' => $request->name
            ]);
            if($insert){
                echo json_encode($insert);
            }else{
                echo json_encode('failed');
            }
        }
    }

    public function GetCompaniesForCustomers(){
        echo json_encode(DB::table('parent_companies')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
        $customer = new Cust;

        $customer->company_name = $request->compName;
        $customer->parent_company = $request->parent_company;
        $customer->industry = $request->industry;
       // $customer->company_poc = $request->poc;
        //$customer->job_title = $request->jobTitle;
        //$customer->business_phone = $request->bussinessPH;
        //$customer->email = $request->email;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->province;
        $customer->country = $request->country;
        $customer->webpage = $request->web_address;
        $customer->remarks = $request->description;
        $customer->created_by = Auth::user()->id;

        $status = $customer->save();
        if($status){
            $add_poc = DB::table('poc')->insert([
                'poc_name' => $request->poc,
                'job_title' => $request->jobTitle,
                'bussiness_ph' => $request->bussinessPH,
                'email' => $request->email,
                'company_name' => $customer->id,
                'added_with_cust' => 1
                ]);
            $_customer_id = $customer->id;
            $check_sub_emp = DB::table('subecribed_notif_new')->whereRaw('emp_id = '.Auth::user()->id.' And customer IS NOT NULL')->first();
            if($check_sub_emp){
                if($check_sub_emp->customer){
                    $customers = explode(",", $check_sub_emp->customer);

                    //For Email
                    $get_email_addresses = DB::table('users')->whereRaw('id IN (Select emp_id from subscribed_notifications WHERE email = 1 AND notification_code_id = 101)')->get();
                    foreach($customers as $customer){
                        DB::table('notifications_list')->insert([
                            'code' => 101,
                            'message' =>'New Customer Added',
                            'customer_id' => $_customer_id,
                            'notif_to' => $customer
                        ]);
                    }
                    foreach($get_email_addresses as $email){
                        if(in_array($email->id , $customers)){
                            $message = 'New Customer <strong>'.$request->compName.'</strong> has been added by: <strong>'.Auth::user()->name.'</strong>.';
                            Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "Customer Added"]));
                        }
                    }
                }
            }

        //     $insert_notification = DB::table('notifications_list')->insert([
        //         'code' => 101,
        //         'message' => 'New Customer added',
        //         'customer_id' => $customer->id
        //     ]);

          
               
            // if(!$get_email_addresses->isEmpty()){
            //     foreach($get_email_addresses as $email){
            //         $message = 'New Customer <strong>'.$request->compName.'</strong> has been added by: <strong>'.Auth::user()->name.'</strong>.';
            //         Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "Customer Added"]));
            //     }
            // }
            echo json_encode($_customer_id);
        }else{
            echo json_encode("failed");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo json_encode(array('info' => DB::table('customers as cust')->selectRaw('`id`, `company_name`, `address`, `city`, `state`, `country`, `webpage`, `remarks`, `parent_company`, `industry`, (Select email from poc where company_name = "'.$id.'" AND added_with_cust = 1) as email, (Select job_title from poc where company_name = "'.$id.'" AND added_with_cust = 1) as job_title, (Select bussiness_ph from poc where company_name = "'.$id.'" AND added_with_cust = 1) as business_phone, (Select poc_name from poc where company_name = "'.$id.'" AND added_with_cust = 1) as company_poc')->where('id', $id)->first(), 'base_url' => URL::to('/')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = Cust::find($id);

        $customer->company_name = $request->compName;
        $customer->parent_company = $request->parent_company;
        $customer->industry = $request->industry;
        //$customer->company_poc = $request->poc;
        //$customer->job_title = $request->jobTitle;
        //$customer->business_phone = $request->bussinessPH;
        //$customer->email = $request->email;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->province;
        $customer->country = $request->country;
        $customer->webpage = $request->web_address;
        $customer->remarks = $request->description;
        $customer->updated_by = Auth::user()->id;

        $status = $customer->save();
        if($status){
            $update_poc = DB::table('poc')->whereRaw('company_name = "'.$id.'" and added_with_cust = 1')->update([
                'poc_name' => $request->poc,
                'job_title' => $request->jobTitle,
                'bussiness_ph' => $request->bussinessPH,
                'email' => $request->email,
                'added_with_cust' => 1
                ]);

            $check_sub_emp = DB::table('subecribed_notif_new')->whereRaw('emp_id = '.Auth::user()->id.' And customer IS NOT NULL')->first();
            if($check_sub_emp){
                if($check_sub_emp->customer){
                    $customers = explode(",", $check_sub_emp->customer);

                    //For Email
                    $get_email_addresses = DB::table('users')->whereRaw('id IN (Select emp_id from subscribed_notifications WHERE email = 1 AND notification_code_id = 101)')->get();
                    foreach($customers as $customer){
                        DB::table('notifications_list')->insert([
                            'code' => 101,
                            'message' =>'Customer Updated',
                            'customer_id' => $id,
                            'notif_to' => $customer
                        ]);
                    }

                    foreach($get_email_addresses as $email){
                        if(in_array($email->id , $customers)){
                            $message = 'Customer <strong>'.$request->compName.'</strong> has been updated by: <strong>'.Auth::user()->name.'</strong>.';
                            Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "Customer Updated"]));
                        }
                    }
                }
            }

            // $insert_notification = DB::table('notifications_list')->insert([
            //     'code' => 101,
            //     'message' => 'Customer updated',
            //     'customer_id' => $id
            // ]);
            
            // if(!$get_email_addresses->isEmpty()){
            //     foreach($get_email_addresses as $email){
            //         $message = 'Customer <strong>'.$request->compName.'</strong> has been updated by: <strong>'.Auth::user()->name.'</strong>.';
            //         Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "Customer Updated"]));
                    
            //     }
            // }
            echo json_encode('success');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($customerId)
    {
        //DB::table('customer_documents')->where('customer_id', $customerId)->delete();
        //DB::table('customer_delivery_ports')->where('customer_id', $customerId)->delete();
        
        if(Storage::exists('public/company/'.Cust::find($customerId)->picture)){
            Storage::delete('public/company/'.Cust::find($customerId)->picture);
        }
        $status = Cust::find($customerId)->delete();
        if($status){
            echo json_encode('success');
        }else{
            echo json_encode('Exception: ' + $status);
        }
    }

    public function viewProfile($customerId){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        if(DB::table('customers')->where('id', $customerId)->first()){
            $pocs = DB::table('poc')->where('company_name', $customerId)->get();
            $complains = DB::table('complains as com')->selectRaw('Date(created_at) as created_at, id, (Select name from users where id = com.created_by) as created_by, remarks, resolved')->where('customer_id', $customerId)->get();
            //echo '<pre>'; print_r($complains); die;
            $cvrs = DB::table('cvr_core as cc')->selectRaw('id, report_created_at, date_of_visit, time_spent, purpose_of_visit, opportunity, bussiness_value, (Select name from users where id = cc.report_created_by) as report_created_by')->where('customer_visited', $customerId)->get();
            $svrs = DB::table('svr_core as cc')->selectRaw('id, report_created_at, date_of_visit, time_spent, (Select name from users where id = cc.report_created_by) as report_created_by')->where('customer_visited', $customerId)->get();
            return view('customer.profile', ['update_customer' => DB::table('customers')->where('id', $customerId)->first(), 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval, 'pocs' => $pocs, 'cvrs' => $cvrs, 'svrs' => $svrs, 'complains' => $complains]);
        }else{
            return redirect('/');
        }
        
    }

    public function updateClientFromProfile(Request $request){
        try{
            DB::table('customers')->where('id', $request->id)->update([
                'company_name' => $request->company_name,
                'company_poc' => $request->poc,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country
            ]);
            echo json_encode('success');
        }catch(\Illuminate\Database\QueryException $ex){ 
            echo json_encode('failed'); 
        }
    }


    //View 
    public function poc_list(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        $customers = DB::table('customers')->get();
        return view('customer.poc_list', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'cust' => $customers, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
    }

    //Save POC
    public function save_poc(Request $request){
        if(DB::table('poc')->where('email', $request->email)->first()){
            echo json_encode('already_exist');
        }else{
            $insert = DB::table('poc')->insert([
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
                echo json_encode('success');
            }else{
                echo json_encode('failed');
            }
        }
    }

    //Edit POC from same page
    public function GetSelectedPOC($id){
        echo json_encode(DB::table('poc')->where('id', $id)->first());
    }

    //Update POC From POC Detail Page
    public function update_poc(Request $request, $id){
        $check = DB::table('poc')->whereRaw('email = "'.$request->email.'" AND id != "'.$id.'"')->first();
        if(!$check){
            try {
            $update = DB::table('poc')->where('id', $id)->update([
                'poc_name' => $request->poc_name,
                'company_name' => $request->company_name,
                'job_title' => $request->jobTitle,
                'bussiness_ph' => $request->businessPH,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'state' =>$request->state           
                ]);
            
                echo json_encode('success');
            }catch(\Illuminate\Database\QueryException $ex){ 
                echo json_encode('failed'); 
            }
        }else{
            echo json_encode('already_exist');
        }
    }

    //Get POC List
    public function GetPOCList(){
        echo json_encode(DB::table('poc as poc')->selectRaw('id, poc_name, bussiness_ph, company_name, job_title, email, (Select company_name from customers where id = poc.company_name) as company')->get());
    }

    //Activate POC
    public function activate_poc(Request $request){
        try{
            $add = DB::table('poc')
            ->where('id', $request->id)->update(
                ['is_active' => 1
                ]);
            if($add){
                echo json_encode('success'); 
            }else{
                echo json_encode('failed'); 
            }

        }catch(\Illuminate\Database\QueryException $ex){ 
            echo json_encode('failed'); 
        }
    }

    //Deactivate POC
    public function deactivate_poc(Request $request){
        try{
            $add = DB::table('poc')
            ->where('id', $request->id)->update(
                ['is_active' => 0
                ]);
            if($add){
                echo json_encode('success'); 
            }else{
                echo json_encode('failed'); 
            }
            
        }catch(\Illuminate\Database\QueryException $ex){ 
            echo json_encode('failed'); 
        }
    }



    //POC Detail Page
    public function poc_detail($id){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        if(DB::table('poc')->where('id', $id)->first()){
            $customers = DB::table('customers')->get();
            return view('customer.poc_detail', ['poc' => DB::table('poc')->where('id', $id)->first(), 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'cust' => $customers, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
        }else{
            return redirect('/');
        }
       
    }

    //Update POC from Detail Page
    public function update_poc_fromPOCDetailPage(Request $request){
        $check = DB::table('poc')->whereRaw('email = "'.$request->email.'" AND id != "'.$request->hidden_poc.'"')->first();
        if(!$check){
            try {
            $update = DB::table('poc')->where('id', $request->hidden_poc)->update([
                'poc_name' => $request->poc_name,
                'company_name' => $request->company_name,
                'job_title' => $request->job_title,
                'bussiness_ph' => $request->bussiness_ph,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'state' =>$request->state           
                ]);
            
                echo json_encode('success');
            }catch(\Illuminate\Database\QueryException $ex){ 
                echo json_encode('failed'); 
            }
        }else{
            echo json_encode('already_exist');
        }
       
    }
    




    public function delete_customer_view(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        return view('customer.delete_customers', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
    }


}
