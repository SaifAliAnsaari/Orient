<?php

namespace App\Http\Controllers;

use App\Customer as Cust;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use DB;
use URL;
use Auth;
use Illuminate\Support\Facades\Storage;
use Validator;

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
        return view('customer.list', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights]);
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
        return view('customer.create');
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
        $customer->company_poc = $request->poc;
        $customer->job_title = $request->jobTitle;
        $customer->business_phone = $request->bussinessPH;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->webpage = $request->web_address;
        $customer->remarks = $request->description;
        $customer->created_by = Auth::user()->id;

        $status = $customer->save();
        if($status){
            $insert_notification = DB::table('notifications_list')->insert([
                'code' => 101,
                'message' => 'New Customer added',
                'customer_id' => $customer->id
            ]);
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
        echo json_encode(array('info' => DB::table('customers as cust')->selectRaw('`id`, `company_name`, `company_poc`, `job_title`, `business_phone`, `address`, `city`, `state`, `country`, `email`, `webpage`, `remarks`, `parent_company`, `industry`')->where('id', $id)->first(), 'base_url' => URL::to('/')));
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
        $customer->company_poc = $request->poc;
        $customer->job_title = $request->jobTitle;
        $customer->business_phone = $request->bussinessPH;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->webpage = $request->web_address;
        $customer->remarks = $request->description;
        $customer->updated_by = Auth::user()->id;

        $status = $customer->save();
        if($status){
            $insert_notification = DB::table('notifications_list')->insert([
                'code' => 101,
                'message' => 'Customer updated',
                'customer_id' => $id
            ]);
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
        return view('customer.profile', ['update_customer' => DB::table('customers')->where('id', $customerId)->first(), 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights]);
    }

    public function updateClientFromProfile(Request $request){
        try{
            DB::table('customers')->where('id', $request->id)->update([
                'company_name' => $request->company_name,
                'company_poc' => $request->poc,
                'business_phone' => $request->phone,
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
        return view('customer.poc_list', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights]);
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
        echo json_encode(DB::table('poc')->get());
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
        return view('customer.poc_detail', ['poc' => DB::table('poc')->where('id', $id)->first(), 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights]);
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
    

   

    //Api Call
    public function gcm_notification(Request $request){
        $status = DB::table('users')->where('id', $request->user()->id)->update(['gcm_dev_token' => $request->device_token]);
        $jar = new JsonApiResponse('success', '200', $status ? "Device token saved succesfully" : $status);
        return $jar->apiResponse();
    }

    public function push_notification($devToken, $title, $status_code, $msg, $data){
        $device_id = $devToken;
        $url = 'https://fcm.googleapis.com/fcm/send';
            $fields = array (
                    'to' => $device_id,
                    'notification' => array (
                        "body" => $msg,
                        'title'   => $title,
                        "icon" => "myicon"
                    ),
                    'data' => array('status' => $status_code, "message" => $data)
            );
        $fields = json_encode ( $fields );
        $headers = array (
            //Yeh id sailaliansaari wali id say generate hoe hai
            //AIzaSyDFOxv9p4LP1-8l4TClG9o6MHoDjFzjZpM

            //Yeh Id Allomate wali id say generate hoe hai
            //AIzaSyDNk-d0dLA-xVc7FeIoQWEGIGR1cje1AVI
                'Authorization: key=' . "AIzaSyDNk-d0dLA-xVc7FeIoQWEGIGR1cje1AVI",
                'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

        $result = curl_exec ( $ch );
        curl_close ( $ch );

        $jar = new JsonApiResponse('success', '200', $result);
        return $jar->apiResponse();
    }

}
