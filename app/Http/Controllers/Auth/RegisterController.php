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
        echo json_encode(User::all());
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
        return view('auth.register', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // echo json_encode($data['company']);
        // die;
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
                'state' => $data['state'],
                'company' => $data['company'],
                'reporting_to' => $data['reporting'],
                'address' => $data['address'],
                'hiring' => $data['hiring'],
                'designation' => $data['designation'],
                'department_id' => $data['department'],
                'salary' => $data['salary'],
                'picture' => $userPicture,
                'password' => Hash::make($data['password']),
            ]);
            if($status){
                echo json_encode('success');
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
        if($this->redirectUrl){return redirect($this->redirectUrl);}

        if($id != Auth::id()){
            return redirect('/');
        }
        return view('includes.edit_profile', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights]);
    }
}
