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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::post('/register', 'Auth\RegisterController@register');

// Route::get('/{database}/newdb', 'SchemaController@createDatabase');

// Route::get('/{table}/create', 'SchemaController@createTable');
// Route::get('/{table}/delete', 'SchemaController@deleteTable');
// Route::get('/{table}/all', 'Real\MyChatController@onMessage1');

// Route::post('client_customer_register', 'UsersController@client_customer_register');
// Route::post('client_customer_login', 'UsersController@client_customer_login');

// Route::post('client_register', 'UsersController@client_register');
// Route::post('client_login', 'UsersController@client_login');

// Route::post('admin_register', 'UsersController@admin_register');
// Route::post('admin_login', 'UsersController@admin_login');

// // Route::get('user', 'TTA\HomeController@getAuthUser');
// Route::get('/user', 'TTA\HomeController@getAuthUser');

// Route::get('/myapps/all', 'TTA\HomeController@getMyApps');
// Route::post('/myapps/new', 'TTA\HomeController@createNewApp');
// Route::put('/myapps/{id}', 'TTA\HomeController@updateMyApp');
// Route::delete('/myapps/{id}', 'TTA\HomeController@deleteMyApp');
// Route::put('/myapps/activeapp/{id}', 'TTA\HomeController@activeMyApp');

// Route::get('/tables', 'TTA\HomeController@getMyTables');
// Route::post('/newtable', 'TTA\HomeController@createTable');
// Route::put('/updatetable', 'TTA\HomeController@updateTable');
// Route::delete('/deletetable/{table}', 'TTA\HomeController@deleteTable');

// Route::get('/active_app/tables', 'TTA\HomeController@getTablesInActiveApp');
// Route::get('/{table}/fields', 'TTA\HomeController@getTableFields');
// Route::get('/{table}/details', 'TTA\HomeController@getTableDetails');
// Route::get('/{table}/field_data_types', 'TTA\HomeController@getTableFieldDataTypes');

// Route::get('/{db_name}/{table}/all', 'TTA\HomeController@index');
// Route::get('/{db_name}/{table}/{id}', 'TTA\HomeController@getRecord');
// Route::post('/{db_name}/{table}/new', 'TTA\HomeController@storeRecord');
// // Route::post('/{db_name}/{table}/{id}', 'TTA\HomeController@updateOrDeleteRecord');
// Route::put('/{db_name}/{table}/{id}', 'TTA\HomeController@updateRecord');
// Route::delete('/{db_name}/{table}/{id}', 'TTA\HomeController@deleteRecord');

// Route::get('/{table}/all', 'TTA\HomeController@index');
// Route::get('/{table}/{id}', 'TTA\HomeController@getRecord');
// Route::post('/{table}/new', 'TTA\HomeController@storeRecord');
// Route::put('/{table}/{id}', 'TTA\HomeController@updateRecord');
// Route::delete('/{table}/{id}', 'TTA\HomeController@deleteRecord');

// Route::post('/image_get', 'TTA\HomeController@getImage');
// Route::post('/file/{pivot_table}/{pivot_field}/{pivot_id}', 'TTA\HomeController@storeFile');


// =================License Routes====================

Route::prefix('license')->group(function() {
	Route::post('/activate', 'GuestController@activateLicense')->name('l.activate.licnese');
	Route::post('/deactivate', 'GuestController@deactivateLicense')->name('l.deactivate.licnese');
});

Route::post('/get-license-key', 'GuestController@getLicenseKey')->name('l.get.licnese.key');

//==================End License Routes ===============

// =================Chat Routes====================

Route::middleware('jst.auth')->prefix('chat')->group(function() {
	Route::match(['get', 'post', 'put', 'delete'], '/', 'ApiController@apiChatRouteGuard');
	// Route::post('/customer_care_app_config', 'ApiController@ccAppConfigGet');
	// Route::post('/request_token', 'ApiController@requestToken');
	// Route::put('/save_resource_id', 'ApiController@saveChatResourceId');
	// Route::post('/my_chats', 'ApiController@getMyChats');
 //    Route::post('/messages', 'ApiController@getMessages');
	// Route::post('/start_chat', 'ApiController@saveNullMessage');
	// Route::post('/waiting_chats', 'ApiController@getWaitingChats');
	// Route::delete('/delete_chat_request', 'ApiController@deleteChatRequest');
	// Route::put('/pick_chat', 'ApiController@pickWaitingChat');
	// Route::post('/save_message', 'ApiController@saveChatMessage');
	// Route::put('/message_status', 'ApiController@updateMessageStatus');
});

//==================End Chat Routes ===============

// =================App Client User Registration Routes====================

// Route::prefix('{app_id}/{auth_provider}')->group(function() {
// 	Route::post('/register', 'ApiController@register')->name('app.user.register');
// 	Route::post('/login', 'ApiController@login')->name('app.user.login');
// });

// =================End App Client Routes====================

// =================App Client Routes====================

// Route::middleware('jst.auth')->prefix('{app_id}/{auth_provider}')->group(function() {
// 	Route::get('/', 'ApiController@index');
// 	Route::get('/{table}/all', 'ApiController@listRecords');
// 	Route::get('/{table}/get/{id}', 'ApiController@getRecord');
// 	Route::post('/{table}/new', 'ApiController@storeRecord');
// 	Route::post('/{table}/mod/{id}', 'ApiController@updateRecord');
// 	Route::post('/{table}/del/{id}', 'ApiController@deleteRecord');
// });

// =================End App Client Routes====================

// =================App Client Routes====================

Route::middleware('jst.auth')->prefix('{query_id}')->group(function() {
	Route::match(['get', 'post', 'put', 'delete'], '/{id?}', 'ApiController@junction')->where('id', '[0-9]+');
});

// =================End App Client Routes====================

// Route::group(['middleware' => ['jwt.auth']], function() {
//     Route::get('logout', 'AuthController@logout');
//     Route::get('test', function(){
//         return response()->json(['foo'=>'bar']);
//     });
// });
Route::fallback(function(){
    return response()->json(['message' => 'Page Not Found. If error persists, contact info@honeyweb.org'], 404);
});