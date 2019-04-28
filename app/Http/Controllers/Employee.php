<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use URL;
use DB;

class Employee extends ParentController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetEmployeeInfo($id)
    {
        echo json_encode(array('employee' => User::find($id), 'base_url' => URL::to('/').'/'));
    }

    public function UpdateEmployee(Request $request, $id)
    {
        $employee = User::find($id);
        
        if(DB::table('users')->whereRaw('username = "'.$request->username.'" AND id != "'.$id.'"')->first()){
            echo json_encode("already_exist");
        }else{
            $employee->name = $request->name;
            $employee->phone = $request->phone;
            $employee->email = $request->email;
            $employee->cnic = $request->cnic;
            $employee->city = $request->city;
            $employee->state = $request->province;
            $employee->company = $request->company;
            $employee->address = $request->address;
            $employee->username = $request->username;
            if($request->password){
                $password = bcrypt($request->password);
                $employee->password = $password;
            }
            $employee->hiring = $request->hiring;
            $employee->salary = $request->salary;
            $employee->designation = $request->designation;
            $employee->reporting_to = $request->reporting;
            $employee->department_id = $request->department;
            
            if($request->hasFile('employeePicture')){
                $completeFileName = $request->file('employeePicture')->getClientOriginalName();
                $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                $extension = $request->file('employeePicture')->getClientOriginalExtension();
                $empPicture = str_replace(' ', '_', $fileNameOnly).'_'.time().'.'.$extension;
                $path = $request->file('employeePicture')->storeAs('public/employees', $empPicture);
                if(Storage::exists('public/employees/'.str_replace('./storage/employees/', '', $employee->picture))){
                    Storage::delete('public/employees/'.str_replace('./storage/employees/', '', $employee->picture));
                }
                $employee->picture = './storage/employees/'.$empPicture;
            }

            if($employee->save()){
                echo json_encode("success");
            }else{
                echo json_encode("failed");
            }
        }
        
    }

    public function getProfile(Request $req){
        $jar = new JsonApiResponse('success', '200', $req->user());
        return $jar->apiResponse();
    }

    public function activate_employee(Request $request){
        $employee = User::find($request->id);
        $employee->is_active = 1;
        if($employee->save()){
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
    }

    public function deactivate_employee(Request $request){
        $employee = User::find($request->id);
        $employee->is_active = 0;
        if($employee->save()){
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
    }

    public function update_user_profile(Request $request){
        $employee = User::find($request->user_id);
        $hashedPassword = $employee->password;

        if($request->current_password){
            if (Hash::check($request->current_password, $hashedPassword)) {
                $employee->password = bcrypt($request->confirm_password);
    
                if($request->hasFile('employeePicture')){
                    $completeFileName = $request->file('employeePicture')->getClientOriginalName();
                    $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                    $extension = $request->file('employeePicture')->getClientOriginalExtension();
                    $empPicture = str_replace(' ', '_', $fileNameOnly).'_'.time().'.'.$extension;
                    $path = $request->file('employeePicture')->storeAs('public/employees', $empPicture);
                    if(Storage::exists('public/employees/'.str_replace('./storage/employees/', '', $employee->picture))){
                        Storage::delete('public/employees/'.str_replace('./storage/employees/', '', $employee->picture));
                    }
                    $employee->picture = './storage/employees/'.$empPicture;
                }
    
                if($employee->save()){
                    echo json_encode("success");
                    die;
                }else{
                    echo json_encode("failed");
                    die;
                }
            }else{
                echo json_encode('not_match');
                die;
            }
        }else if($request->hasFile('employeePicture')){
            $test = $employee->picture;
            $completeFileName = $request->file('employeePicture')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('employeePicture')->getClientOriginalExtension();
            $empPicture = str_replace(' ', '_', $fileNameOnly).'_'.time().'.'.$extension;
            $path = $request->file('employeePicture')->storeAs('public/employees', $empPicture);
            if(Storage::exists('public/employees/'.str_replace('./storage/employees/', '', $employee->picture))){
                Storage::delete('public/employees/'.str_replace('./storage/employees/', '', $employee->picture));
            }
            $employee->picture = './storage/employees/'.$empPicture;
            $employee->save();
            echo json_encode("success");
            die;
        }else {
            echo json_encode("empty"); 
            die;
        }

        
    }

}
