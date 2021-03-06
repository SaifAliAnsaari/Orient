<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ParentController;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Input;
use URL;
use Auth;
use DB;
use Illuminate\Http\Request;

class RegisterController extends ParentController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     // $this->middleware('guest');
    // }

    protected $request;

    public function __contruct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:6'],
            'username' => ['required', 'string', 'max:100', 'unique:users']
        ]);
    }

    public function EmployeesList(){
        echo json_encode(DB::table('users as u')->selectRaw('id, name, username, email, phone, (Select city_name from pick_up_delivery where id = u.city) as city')->get());
        // echo json_encode(User::all());
    }

    public function UploadUserImage(Request $request){
        echo json_encode("HERE");die;
        // if($request->hasFile('employeePicture')){
        //     echo json_encode("FILE");die;
        // }
    }

    public function showRegistrationForm(){
        parent::get_notif_data();
        parent::VerifyRights();if($this->redirectUrl){return redirect($this->redirectUrl);}
        $employees = User::all();

        $data = DB::table('pick_up_delivery')->get();

        $provinces = [];
        $counter = 0;
        array_map(function($item) use($data, &$provinces, &$counter, &$cities){
            $provinces[$counter]['province'] = $item['province'];
            $provinces[$counter]['id'] = $item['id'];
            $counter ++;
        }, json_decode($data, true));
        
        $filtered_privinces = $this->unique_multidim_array($provinces, "province");
        $designations = DB::table('designations')->get();

        return view('auth.register', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'emp' => $employees, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval, 'provinces' => $filtered_privinces, 'data' => $data, 'designations' => $designations]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $test = $data['super_admin'];
        $access_rights = ['/home', '/Customer_list', '/CustomerProfile', '/edit_profile', '/poc_list', '/poc_detail', '/new_cvr', '/cvr_list', '/cvr_preview', '/edit_cvr', '/disapproved_detail', '/notifications', '/notification_prefrences', '/complaints_settings', '/generate_complaints', '/complaints_list', '/resolved_complains', '/new_svr', '/svr_list', '/edit_svr', '/svr_preview', '/disapproved_svr_detail'];
        $userPicture = '';
        if(isset($_FILES["employeePicture"])){
            $userPicture = './storage/employees/' . time().'-'.str_replace(' ', '_', basename($_FILES["employeePicture"]["name"]));
            move_uploaded_file($_FILES["employeePicture"]["tmp_name"], $userPicture);
        }
        if(is_null(User::whereEmail($data['email'])->first())){
            $status = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'username' => $data['username'],
                'cnic' => $data['cnic'],
                'city' => $data['city'],
                'state' => $data['province'],
                'company' => $data['company'],
                'reporting_to' => $data['reporting'],
                'address' => $data['address'],
                'hiring' => $data['hiring'],
                'designation' => $data['designation'],
                'department_id' => $data['department'],
                'super_admin' => $data['super_admin'],
                //'salary' => $data['salary'],
                'division' => $data['division'],
                'picture' => $userPicture,
                'password' => Hash::make($data['password']),
            ]);
            if($status){
                DB::table('subecribed_notif_new')->insert([
                    'cvr' => $data['hidden_cvr_emp'],
                    'svr' => $data['hidden_svr_emp'],
                    'complaint' => $data['hidden_complaint_emp'],
                    'customer' => $data['hidden_customers_emp'],
                    'emp_id' => $status->id
                ]);
               
                // DB::table('subscribed_notifications')->insert([
                //     'notification_code_id' => $data['hidden_cvr_emp'],
                //     'email' => 0,
                //     'web' => 1,
                //     'emp_id' => $status->id,
                //     'subscribed_at' => date('Y-m-d H:i:s')
                // ]);
                $codes = DB::table('notifications_code')->get();
                foreach($codes as $cd){
                    DB::table('subscribed_notifications')->insert([
                        'notification_code_id' => $cd->code,
                        'email' => 0,
                        'web' => 1,
                        'emp_id' => $status->id
                    ]);
                }
                foreach($access_rights as $rights){
                    DB::table('access_rights')->insert([
                        "employee_id" => $status->id,
                        "access" => $rights
                    ]);
                }
                
                echo json_encode($status->id);
                die;
            }else{
                echo json_encode("failed");
                die;
            }
        }else{
            echo json_encode('email_exist'); 
            die;
        }
    }

    public function edit_profile($id){
        parent::get_notif_data();
        parent::VerifyRights();
        $notifications_name = DB::table('notifications_code')->get();
        if($this->redirectUrl){return redirect($this->redirectUrl);}

        $data = DB::table('users as us')->selectRaw('(Select city_name from pick_up_delivery where id = us.city) as city_name, (Select name from users where id = us.reporting_to) as reporting_to, (Select name from designations where id = us.designation) as designation')->where('id', Auth::user()->id)->first();
        if($id != Auth::id()){
            return redirect('/');
        }
        return view('includes.edit_profile', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'unread_notif' => $this->unread_notif_approval, 'approval_notif' => $this->approval_notif, 'notifications_code' => $notifications_name, 'data' => $data]);
    }





    
    public function pick_up(){
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        parent::get_notif_data();
        return view('includes.pick_up', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
    }

    public function PickUp_save(Request $request){
       // echo json_encode($request->location); die;
        // $check = DB::table('pick_up_delivery')->where('city_name', $request->city_name)->first();
        // if($check){
        //     echo json_encode('already_exist');
        // }else{
            $insert_pickup_delivery = DB::table('pick_up_delivery')->insert([
                'city_name' => $request->city_name,
                'province' => $request->province,
                'city_short' => $request->city_short_code,
                //'services' => json_encode($request->location),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id
                ]);
            if($insert_pickup_delivery){
                echo json_encode("success");
            }else{
                echo json_encode("failed");
            }
        //}
    }

    public function GetPickUpList(){
        echo json_encode(DB::table('pick_up_delivery')->get());
    }

    public function pickUp_data($id){
        // $core = DB::table('pick_up_delivery')->where('id', $request->id)->first();
        // //$users = DB::table('users')->get()->toArray();
        // $select_name = array('1' => 'Pick Up', '2' => 'Delivery');
        // $mem = [];
        // $counter = 0;

        // $names = [];
        // array_map(function($item) use($select_name, &$names, &$counter){
            
        //     $names[$counter]['name'] = $select_name[array_search($item, array_column($select_name, "id"))]->name;
        //     $names[$counter]['ids'] = $select_name[array_search($item, array_column($select_name, "id"))]->id;
        //     $counter ++;
        // }, json_decode($core->assign_to, true));

        // // $data = [];
        // // $data['core'] = $core;
        // // $data['names'] = $names; 
        // echo json_encode(array('core' => $core, 'names' => $names));
        echo json_encode(DB::table('pick_up_delivery')->where('id', $id)->first());
    }

    public function delete_pickUp(Request $request){
        $delete = DB::table('pick_up_delivery')->where('id', $request->id)->delete();
        if($delete){
            echo json_encode('success');
        }else{
            echo json_encode('success');
        }
    }

    public function update_pickup(Request $request){
        try{
            DB::table('pick_up_delivery')->where('id', $request->id)->update([
                'city_name' => $request->city_name,
                'province' => $request->province,
                'city_short' => $request->city_short_code,
                //'services' => json_encode($request->location),
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s')
                ]);
            echo json_encode('success');
        }catch(\Illuminate\Database\QueryException $ex){ 
            echo json_encode('failed'); 
        }
    }




    public function designations(){
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        parent::get_notif_data();
        return view('includes.designations', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
    }

    public function save_designation(Request $request){
        if(DB::table('designations')->where('name', $request->designation_name)->first()){
            echo json_encode('already exist');
        }else{
            $insert = DB::table('designations')->insert([
                'name' => $request->designation_name
            ]);
            if($insert){
                echo json_encode('success');
            }else{
                echo json_encode('failed');
            }
        }
    }

    public function DesignationsList(){
        echo json_encode(DB::table('designations')->get());
    }

    public function get_designation($id){
        echo json_encode(DB::table('designations')->where('id', $id)->first());
    }

    public function UpdateDesignation(Request $request, $id){
        if(DB::table('designations')->whereRaw('name = "'.$request->designation_name.'" and id != '.$id)->first()){
            echo json_encode('already exist');
        }else{
            try{
                DB::table('designations')->where('id', $id)->update([
                    'name' => $request->designation_name
                    ]);
                echo json_encode('success');
            }catch(\Illuminate\Database\QueryException $ex){ 
                echo json_encode('failed'); 
            }
        }
    }

    public function delete_designation(Request $request){
        $delete = DB::table('designations')->where('id', $request->id)->delete();
        if($delete){
            echo json_encode('success');
        }else{
            echo json_encode('failed');
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
