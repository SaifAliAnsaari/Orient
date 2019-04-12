<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use URL;
use Auth;

class Categories extends ParentController
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

     public function main_category(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
         return view('categories.main_category', ['notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
     }

     public function save_main_cat(Request $request){
         if(DB::table('main_categories')->where('name', $request->main_cat_name)->first()){
            echo json_encode('already_exist');
         }else{
            $insert = DB::table('main_categories')->insert(['name' => $request->main_cat_name]);
            if($insert){
                echo json_encode('success');
            }else{
                echo json_encode('failed');
            }
         } 
     }

     public function GetMainCategories(){
         echo json_encode(DB::table('main_categories')->get());
     }

     public function GetSelectedMainCat($id){
        echo json_encode(DB::table('main_categories')->where('id', $id)->first());
     }

     public function update_main_cat(Request $request, $id){
        try{
            if(DB::table('main_categories')->whereRaw('name = "'.$request->main_cat_name.'" AND id != '.$id)->first()){
                echo json_encode('already_exist');
            }else{
                DB::table('main_categories')->where('id', $id)->update(['name' => $request->main_cat_name]);
                echo json_encode('success');
            }
        }catch(\Illuminate\Database\QueryException $ex){ 
            echo json_encode('failed'); 
        }
     }

     public function delete_main_cat($id){
        $delete = DB::table('main_categories')->where('id', $id)->delete();
        if($delete){
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
     }



     public function sub_category(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        return view('categories.sub_category', ['main_cat' => DB::table('main_categories')->get(), 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
    }

    public function save_sub_cat(Request $request){
        if(!DB::table('sub_categories')->whereRaw('name = "'.$request->sub_cat_name.'" AND main_cat_id = '.$request->select_main_cat)->first()){
            $insert = DB::table('sub_categories')->insert([
                'name' => $request->sub_cat_name,
                'main_cat_id' =>$request->select_main_cat
            ]);
            if($insert){
                echo json_encode('success');
            }else{
                echo json_encode('failed');
            }
        }else{
            echo json_encode('already_exist');
        }
    }

    public function GetSubCategories(){
        echo json_encode(DB::table('sub_categories as sc')->selectRaw('id, name, main_cat_id, (Select name from main_categories where id = sc.main_cat_id) as main_cat_name')->get());
    }

    public function GetSelectedSubCat($id){
        echo json_encode(DB::table('sub_categories')->where('id', $id)->first());
    }

    public function update_sub_cat(Request $request, $id){
        if(DB::table('sub_categories')->whereRaw('name = "'.$request->sub_cat_name.'" AND main_cat_id = "'.$request->select_main_cat.'" AND id != '.$id)->first()){
            echo json_encode('already_exist');
        }else{
            try{
                DB::table('sub_categories')->where('id', $id)->update(['name' => $request->sub_cat_name, 'main_cat_id' => $request->select_main_cat]);
                echo json_encode('success');
            }catch(\Illuminate\Database\QueryException $ex){ 
                echo json_encode('failed'); 
            }
        }
    }

    public function delete_sub_cat($id){
        $delete = DB::table('sub_categories')->where('id', $id)->delete();
        if($delete){
            echo json_encode('success');
        }else{
            echo json_encode('failed');
        }
    }



    public function product_category(){
        parent::get_notif_data();
        parent::VerifyRights();
        if($this->redirectUrl){return redirect($this->redirectUrl);}
        return view('categories.product_category', ['sub_cat' => DB::table('sub_categories')->get(), 'notifications_counts' => $this->notif_counts, 'notif_data' => $this->notif_data, 'all_notif' => $this->all_notification, 'check_rights' => $this->check_employee_rights, 'approval_notif' => $this->approval_notif, 'unread_notif' => $this->unread_notif_approval]);
    }

    public function save_product_cat(Request $request){
        if(!DB::table('product_categories')->whereRaw('name = "'.$request->product_cat_name.'" AND sub_cat_id = '.$request->select_sub_cat)->first()){
            $insert = DB::table('product_categories')->insert([
                'name' => $request->product_cat_name,
                'sub_cat_id' =>$request->select_sub_cat
            ]);
            if($insert){
                echo json_encode('success');
            }else{
                echo json_encode('failed');
            }
        }else{
            echo json_encode('already_exist');
        }
    }

    public function GetProductCategories(){
        echo json_encode(DB::table('product_categories as pc')->selectRaw('id, name, sub_cat_id, (Select name from sub_categories where id = pc.sub_cat_id) as sub_cat_name')->get());
    }
}
