<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/profile', 'Employee@getProfile');
Route::middleware('auth:api')->get('/customers', 'Customer@getCustomers');
Route::middleware('auth:api')->post('/storeCustomer', 'Customer@storeCustomer');
Route::middleware('auth:api')->get('/getCustomerMeta', 'Customer@customerMeta');
Route::middleware('auth:api')->post('/updateCustomer', 'Customer@updateCustomer');
Route::middleware('auth:api')->post('/storeCustomerBulk', 'Customer@storeCustomerBulk');
Route::middleware('auth:api')->post('/updateCustomerBulk', 'Customer@updateCustomerBulk');
Route::middleware('auth:api')->get('/getUnsyncedData', 'Customer@getUnsyncCustomers');
Route::middleware('auth:api')->post('/SyncedComplete', 'Customer@updateSyncedData');
Route::middleware('auth:api')->post('/gcm_notification', 'Customer@gcm_notification');
Route::middleware('auth:api')->post('/zone_sync_complete', 'Customer@syncZoneChanged');
