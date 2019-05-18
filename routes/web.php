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
Route::get('/complaints_settings', 'ComplaintsManagment@complaints_settings');
Route::get('/generate_complaints', 'ComplaintsManagment@generate_complaints');
Route::get('/complaints_list', 'ComplaintsManagment@complaints_list');
Route::get('/resolved_complains', 'ComplaintsManagment@resolved_complains');
Route::get('/disapproved_detail/{id}', 'ReportManagment@disapproved_detail');
Route::get('/cities', 'Auth\RegisterController@pick_up');
Route::get('/designations', 'Auth\RegisterController@designations');
Route::get('/new_svr', 'ReportManagment@new_svr');
Route::get('/svr_list', 'ReportManagment@svr_list');
Route::get('/edit_svr/{id}', 'ReportManagment@edit_svr');
Route::get('/svr_preview/{id}', 'ReportManagment@svr_preview');
Route::get('/disapproved_svr_detail/{id}', 'ReportManagment@disapproved_svr_detail');

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
Route::post('/save_cvr_approval', 'ReportManagment@save_cvr_approval');
Route::post('/save_complain_type', 'ComplaintsManagment@save_complain_type');
Route::post('/save_complain', 'ComplaintsManagment@save_complain');
Route::post('/resolve_complain', 'ComplaintsManagment@resolve_complain');
Route::post('/PickUp_save', 'Auth\RegisterController@PickUp_save');
Route::post('/update_pickup', 'Auth\RegisterController@update_pickup');
Route::post('/save_designation', 'Auth\RegisterController@save_designation');
Route::get('/save_parent_company', 'Customer@save_parent_company');
Route::post('/save_svr', 'ReportManagment@save_svr');
Route::post('/update_svr', 'ReportManagment@update_svr');
Route::post('/save_svr_approval', 'ReportManagment@save_svr_approval');

//update
Route::post('/update_user_profile', 'Employee@update_user_profile');
Route::post('/update_user_profile_pic', 'Employee@update_user_profile_pic');
Route::post('/update_poc_fromPOCDetailPage', 'Customer@update_poc_fromPOCDetailPage');
Route::post('/update_main_cat/{id}', 'Categories@update_main_cat');
Route::post('/update_sub_cat/{id}', 'Categories@update_sub_cat');
Route::get('/updateClientFromProfile', 'Customer@updateClientFromProfile');
Route::post('/UpdateDesignation/{id}', 'Auth\RegisterController@UpdateDesignation');


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
Route::get('/get_cust_address', 'ReportManagment@get_cust_address');
Route::get('/download_pdf/{id}', 'ReportManagment@download_pdf');
Route::get('/send_mail/{id}', 'ReportManagment@send_mail');
Route::get('/complains_type_list', 'ComplaintsManagment@complains_type_list');
Route::get('/get_complain_type_data', 'ComplaintsManagment@get_complain_type_data');
Route::get('/delete_complain_type', 'ComplaintsManagment@delete_complain_type');
Route::get('/get_complains_list', 'ComplaintsManagment@get_complains_list');
Route::get('/get_resolved_complains_list', 'ComplaintsManagment@get_resolved_complains_list');
Route::get('/get_complain_detail', 'ComplaintsManagment@get_complain_detail');
Route::get('/GetPickUpList', 'Auth\RegisterController@GetPickUpList');
Route::get('/pickUp_data/{id}', 'Auth\RegisterController@pickUp_data');
Route::get('/DesignationsList', 'Auth\RegisterController@DesignationsList');
Route::get('/get_designation/{id}', 'Auth\RegisterController@get_designation');
Route::get('/GetCompaniesForCustomers', 'Customer@GetCompaniesForCustomers');
Route::get('/GetSVRList', 'ReportManagment@GetSVRList');
Route::get('/GetCurrentSvr/{id}', 'ReportManagment@GetCurrentSvr');
Route::get('/download_svr_pdf/{id}', 'ReportManagment@download_svr_pdf');
Route::get('/send_svr_mail/{id}', 'ReportManagment@send_svr_mail');

//Delete
Route::get('/delete_main_cat/{id}', 'Categories@delete_main_cat');
Route::get('/delete_sub_cat/{id}', 'Categories@delete_sub_cat');
Route::get('/delete_pickUp', 'Auth\RegisterController@delete_pickUp');
Route::get('/delete_designation', 'Auth\RegisterController@delete_designation');

//Deactive or Active Employee
Route::get('/activate_employee', 'Employee@activate_employee');
Route::get('/deactivate_employee', 'Employee@deactivate_employee');

//Deactive or Active Customer
Route::get('/activate_customer', 'Customer@activate_customer');
Route::get('/deactivate_customer', 'Customer@deactivate_customer');

//Activate or Deactive POC
Route::get('/activate_poc', 'Customer@activate_poc');
Route::get('/deactivate_poc', 'Customer@deactivate_poc');
