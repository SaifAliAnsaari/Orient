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
        return view('customer.list', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'parent_comp' => $parent_comp, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
     }

    //Ajax Call from list-customers.js
    public function CustomersList(Request $request){
        echo json_encode(DB::table('customers')->get());
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // parent::get_notif_data();
        // parent::VerifyRights();
        // if($this->redirectUrl){return redirect($this->redirectUrl);}
        // $parent_comp = DB::table('customers')->get();
        // return view('customer.create', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'parent_comp' => $parent_comp]);
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
        $customer->state = $request->state;
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

            $insert_notification = DB::table('notifications_list')->insert([
                'code' => 101,
                'message' => 'New Customer added',
                'customer_id' => $customer->id
            ]);

           $get_email_addresses = DB::table('users')->select('email')->whereRaw('id IN (Select emp_id from subscribed_notifications WHERE email = 1 AND notification_code_id = 101)')->get();
               
            if(!$get_email_addresses->isEmpty()){
                foreach($get_email_addresses as $email){
                    $message = 'New Customer "'.$request->compName.'" has been added in Orient.';
                    Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "Customer Added"]));
                }
            }
            echo json_encode('success');
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
        $customer->state = $request->state;
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

            $insert_notification = DB::table('notifications_list')->insert([
                'code' => 101,
                'message' => 'Customer updated',
                'customer_id' => $id
            ]);
            $get_email_addresses = DB::table('users')->select('email')->whereRaw('id IN (Select emp_id from subscribed_notifications WHERE email = 1 AND notification_code_id = 101)')->get();
            if(!$get_email_addresses->isEmpty()){
                foreach($get_email_addresses as $email){
                    $message = 'Customer "'.$request->compName.'" has been updated in Orient.';
                    Mail::to($email->email)->send(new SendMailable(["message" => $message, "subject" => "Customer Updated"]));
                    
                }
            }
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
        return view('customer.profile', ['update_customer' => DB::table('customers')->where('id', $customerId)->first(), 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
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
        $customers = DB::table('customers')->get();
        return view('customer.poc_detail', ['poc' => DB::table('poc')->where('id', $id)->first(), 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'cust' => $customers, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
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
    


}
