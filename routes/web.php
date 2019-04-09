<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/GetCustomersList', 'Customer@CustomersList');
Route::Resource('/Customer', 'Customer');
Route::get('/Customer_list', 'Customer@CustomerView');
Route::get('/EmployeesList', 'Auth\RegisterController@EmployeesList');
Route::post('/UploadUserImage', 'Auth\RegisterController@UploadUserImage');
Route::get('/Employee/{id}', 'Employee@GetEmployeeInfo');
Route::post('/UpdateEmployee/{id}', 'Employee@UpdateEmployee');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::post('/update_poc/{id}', 'Customer@update_poc');

//Views
Route::get('/edit_profile/{id}', 'Auth\RegisterController@edit_profile');
Route::get('/CustomerProfile/{customerId}', 'Customer@viewProfile');
Route::get('/poc_list', 'Customer@poc_list');
Route::get('/poc_detail/{id}', 'Customer@poc_detail');
Route::get('/new_cvr', 'ReportManagment@new_cvr');
Route::get('/main_category', 'Categories@main_category');
Route::get('/sub_category', 'Categories@sub_category');
Route::get('/product_category', 'Categories@product_category');
Route::get('/notification_prefrences', 'NotificationCenter@notification_prefrences');
Route::get('/notifications', 'NotificationCenter@notifications');
Route::get('/SCFAR', 'AccessRights@select_employee');
Route::get('/access_rights/{id}', 'AccessRights@access_rights');
Route::get('/cvr_list', 'ReportManagment@cvr_list');
Route::get('/cvr_preview/{id}', 'ReportManagment@cvr_preview');
Route::get('/edit_cvr/{id}', 'ReportManagment@edit_cvr');

//Save
Route::post('/save_poc', 'Customer@save_poc');
Route::post('/save_main_cat', 'Categories@save_main_cat');
Route::post('/save_sub_cat', 'Categories@save_sub_cat');
Route::post('/save_product_cat', 'Categories@save_product_cat');
Route::post('/save_poc_from_modal', 'ReportManagment@save_poc_from_modal');
Route::post('/save_cvr', 'ReportManagment@save_cvr');
Route::post('/update_cvr', 'ReportManagment@update_cvr');
Route::post('/save_pref_against_emp', 'NotificationCenter@save_pref_against_emp');
Route::post('/read_notif_four', 'NotificationCenter@read_notif_four');
Route::post('/saveAccessRights', 'AccessRights@saveAccessRights');

//update
Route::post('/update_user_profile', 'Employee@update_user_profile');
Route::post('/update_poc_fromPOCDetailPage', 'Customer@update_poc_fromPOCDetailPage');
Route::post('/update_main_cat/{id}', 'Categories@update_main_cat');
Route::post('/update_sub_cat/{id}', 'Categories@update_sub_cat');
Route::get('/updateClientFromProfile', 'Customer@updateClientFromProfile');

//Get Data
Route::get('/GetPOCList', 'Customer@GetPOCList');
Route::get('/GetSelectedPOC/{id}', 'Customer@GetSelectedPOC');
Route::get('/GetCustForCVR', 'ReportManagment@GetCustForCVR');
Route::get('/GetMainCategories', 'Categories@GetMainCategories');
Route::get('/GetSubCategories', 'Categories@GetSubCategories');
Route::get('/GetProductCategories', 'Categories@GetProductCategories');
Route::get('/GetSelectedMainCat/{id}', 'Categories@GetSelectedMainCat');
Route::get('/GetSelectedSubCat/{id}', 'Categories@GetSelectedSubCat');
Route::get('/notif_pref_against_emp/{id}', 'NotificationCenter@notif_pref_against_emp');
Route::get('/GetEmployeeListForRights', 'AccessRights@GetEmployeeListForRights');
Route::get('/GetCVRList', 'ReportManagment@GetCVRList');
Route::get('/GetCurrentCvr/{id}', 'ReportManagment@GetCurrentCvr');
Route::get('/download_pdf/{id}', 'ReportManagment@download_pdf');

//Delete
Route::get('/delete_main_cat/{id}', 'Categories@delete_main_cat');
Route::get('/delete_sub_cat/{id}', 'Categories@delete_sub_cat');

//Deactive or Active Employee
Route::get('/activate_employee', 'Employee@activate_employee');
Route::get('/deactivate_employee', 'Employee@deactivate_employee');

//Deactive or Active Customer
Route::get('/activate_customer', 'Customer@activate_customer');
Route::get('/deactivate_customer', 'Customer@deactivate_customer');

//Activate or Deactive POC
Route::get('/activate_poc', 'Customer@activate_poc');
Route::get('/deactivate_poc', 'Customer@deactivate_poc');
