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

Route::view('/', 'welcome');
Route::get('/up', 'TempController@up'); // temp route

//====================Site Verification Routes====================
Route::get('/honeyweb-domain-verification', function () {
    return "$2y$10$uyohYtWlS/h598L7/FRXl.I8L6tMlfEONA4GZOc2Gz4Skk21rFJZy";
});
//====================End of Site Verification Routes=============


//====================Authentication Routes====================
Route::get('email_verified/{id}', 'Auth\RegisterController@email_verified');
Auth::routes();
//====================Authentication Routes Ends====================

//====================Guest Routes====================
Route::post('/theme', 'GuestController@setTheme')->name("theme");
Route::get('/', 'GuestController@homeView')->name('c.welcome');
// Route::get('/docs', 'GuestController@docsView')->name('c.docs');
//====================End of Guest Routes=============

//====================App Routes====================
Route::prefix('app')->group(function() {
	Route::post('/new-app', 'UserController@createNewApp')->name('c.create.new.app');
	Route::get('/app-list', 'UserController@appListView')->name('c.app.list.view');
	Route::get('/app-origins/{id}', 'UserController@appOriginsView')->name('c.app.origins.view');
	Route::post('/new-origin/{id}', 'UserController@addNewOrigin')->name('c.app.new.origin.submit');
	Route::post('/delete-origin/{id}', 'UserController@deleteOrigin')->name('c.app.delete.origin');
	Route::post('/activate', 'UserController@appActivate')->name('c.app.activate');
	Route::post('/update', 'UserController@updateApp')->name('c.update.app');
	Route::post('/delete/{id}', 'UserController@deleteApp')->name('c.delete.app');
	Route::get('/sql/{id?}', 'UserController@exportDb')->name('c.app.sql.export');
	Route::get('/csv', 'UserController@exportAppsToCSV')->name('c.app.csv.export');

	Route::get('/log_view', 'UserController@logView')->name('c.app.log');
});
//====================End of App Routes=============

//====================License Routes====================
Route::prefix('license')->group(function() {
	Route::post('/new-license', 'UserController@createNewLicense')->name('l.create.new.license');
	Route::get('/license-list', 'UserController@licenseListView')->name('l.license.list.view');
	Route::get('/license-details/{id}', 'UserController@licenseDetailsView')->name('l.license.details.view');
	Route::get('/test-bench', 'UserController@testBenchView')->name('l.test.bench.view');
	Route::post('/update/{id}', 'UserController@updateLicense')->name('l.update.license');
	Route::post('/delete/{id}', 'UserController@deleteLicense')->name('l.delete.license');
});
//====================End of License Routes=============

//====================Table Routes====================
Route::prefix('table')->group(function() {
	Route::get('/table-list', 'UserController@myTableListView')->name('c.table.list.view');
	Route::get('/new-table-view', 'UserController@createNewTableView')->name('c.db.new.table');
	Route::post('/new-table', 'UserController@createNewTable')->name('c.db.new.table.submit');
	Route::get('/add-columns-view', 'UserController@addColumnsView')->name('c.db.add.columns');
	Route::post('/add-columns', 'UserController@addColumns')->name('c.db.add.columns.submit');
	Route::get('/get-columns', 'UserController@getColumns')->name('c.db.get.columns');
	Route::post('/rename-column', 'UserController@renameColumn')->name('c.db.rename.column.submit');
	Route::post('/delete-column', 'UserController@deleteColumn')->name('c.db.delete.column.submit');
	Route::post('/add-index', 'UserController@addIndex')->name('c.db.add.index.submit');
	Route::post('/remove-index', 'UserController@removeIndex')->name('c.db.remove.index.submit');
	Route::get('/crud-view', 'UserController@crudTableView')->name('c.db.crud.table');
	Route::get('/add-record-view', 'UserController@addRecordView')->name('c.db.add.record');
	Route::post('/add-record', 'UserController@addRecord')->name('c.db.add.record.submit');
	Route::get('/edit-record-view', 'UserController@editRecordView')->name('c.db.edit.record');
	Route::post('/edit-record', 'UserController@editRecord')->name('c.db.edit.record.submit');
	Route::delete('/delete-record', 'UserController@deleteRecord')->name('c.db.delete.record');
	Route::post('/rename', 'UserController@renameTable')->name('c.db.rename.table');
	Route::post('/truncate', 'UserController@truncateTable')->name('c.truncate.table');
	Route::post('/delete', 'UserController@deleteTable')->name('c.delete.table');
});
//====================End of Table Routes=============

//====================Query Routes====================
Route::prefix('query')->group(function() {
	Route::get('/query-list', 'UserController@queryListView')->name('c.query.list.view');
	Route::get('/new-query-view', 'UserController@createNewQueryView')->name('c.create.new.query');
	Route::get('/get-all-columns', 'UserController@getAllColumns')->name('c.q.get.all.columns');
	Route::post('/new-query', 'UserController@createNewQuery')->name('c.create.new.query.submit');
	Route::get('/query-details/{id}', 'UserController@queryDetailsView')->name('c.query.details.view');
	Route::put('/update/{id}', 'UserController@updateQuery')->name('c.update.query');
	Route::delete('/delete', 'UserController@deleteQuery')->name('c.delete.query');
});
//====================End of License Routes=============

//====================Files Routes====================
Route::prefix('files')->group(function() {
	Route::get('/csv-export/{table}', 'UserController@exportToCSV')->name('c.csv.export');
	Route::get('/txt-export/{table}', 'UserController@exportToTXT')->name('c.txt.export');
	Route::get('/json-export/{table}', 'UserController@exportToJSON')->name('c.json.export');
	Route::post('/csv-import-create', 'UserController@importCreateCSV')->name('c.csv.import.create');
	Route::post('/csv-import-update', 'UserController@importUpdateCSV')->name('c.csv.import.update');
	Route::post('/json-import-create', 'UserController@importCreateJSON')->name('c.json.import.create');
	Route::post('/json-import-update', 'UserController@importUpdateJSON')->name('c.json.import.update');
	Route::get('/files-view', 'UserController@filesView')->name('c.files.view');
	Route::post('/upload_files', 'UserController@uploadFiles')->name('c.files.upload.files');
	Route::get('/{id}', 'UserController@downloadFile')->name('c.files.download');
	Route::post('/replace-file', 'UserController@replaceFile')->name('c.files.replace');
	Route::post('/delete-file', 'UserController@deleteFile')->name('c.files.delete');
});
//====================End of Files Routes=============

//====================Email Routes====================
Route::prefix('email')->group(function() {
	Route::get('/email-users-list', 'UserController@emailListView')->name('c.email.list.view');

	Route::get('/domain-list', 'UserController@domainListView')->name('c.domain.list.view');
	Route::post('/domain-new', 'UserController@addNewDomain')->name('c.domain.add.new');
	Route::get('/domain-verify/{id}', 'UserController@verifyNewDomainView')->name('c.domain.verify');
	Route::delete('/domain-delete', 'UserController@deleteDomain')->name('c.domain.delete');

	Route::get('/alias-list', 'UserController@aliasListView')->name('c.alias.list.view');
	Route::post('/alias-new', 'UserController@addNewAlias')->name('c.alias.add.new');
	Route::post('/alias-verify', 'UserController@verifyAlias')->name('c.alias.verify');
	Route::delete('/alias-delete', 'UserController@deleteAlias')->name('c.alias.delete');

	Route::get('/template-list', 'UserController@templateListView')->name('c.template.list.view');
	Route::post('/template-new', 'UserController@addNewTemplate')->name('c.template.add.new');
	Route::post('/template-verify', 'UserController@verifyTemplate')->name('c.template.verify');
	Route::delete('/template-delete', 'UserController@deleteTemplate')->name('c.template.delete');

	Route::get('/create-email-account-view', 'UserController@createEmailAccountView')->name('c.email.new.account');
	Route::post('/new-email-account', 'UserController@addNewUser')->name('c.email.new.user.submit');
	Route::delete('/delete', 'UserController@deleteEmailAccount')->name('c.email.delete.user');

	Route::post('/get-txt', 'UserController@getTxtRecord')->name('c.email.get.txt');
	Route::post('/get-page', 'UserController@getPageContents')->name('c.email.get.page');
});
//====================End of Email Routes=============

//====================Push Notification Routes====================
Route::prefix('push')->group(function() {
	Route::post('/save-subscription', 'GuestController@saveSubscription')->name('c.push.save_subscription');
	Route::get('/messages', 'UserController@messageList')->name('c.push.messages');
	Route::get('/new_message', 'UserController@createMessageView')->name('c.push.new.msg');
	Route::post('/new_message_submit', 'UserController@createMessage')->name('c.push.new.msg.submit');
	Route::get('/update_message/{id}', 'UserController@updateMessageView')->name('c.push.update.msg');
	Route::post('/update_message_submit', 'UserController@updateMessage')->name('c.push.update.msg.submit');
	Route::post('/copy_message', 'UserController@copyMessage')->name('c.push.copy.msg');
	Route::post('/delete_message', 'UserController@deleteMessage')->name('c.push.del.msg');
	Route::get('/broadcast/{id}', 'UserController@broadcast')->name('c.push.broadcast');
});
//====================End of Push Notification Routes=============

//====================Push Notification Routes====================
// Route::prefix('chat')->group(function() {
// 	Route::view('/messages', 'cb.messages');
// 	Route::post('/save_resource_id', 'UserController@saveChatResourceId')->name('c.chat.srid');
// 	Route::get('/my_chats', 'UserController@getMyChats')->name('c.chat.my');
// 	Route::post('/save_message', 'UserController@saveChatMessage')->name('c.chat.save_message');
// 	Route::get('/chatspace', 'UserController@chatspaceView')->name('c.chat.chatspace');
//     Route::get('/messages-', 'UserController@messagesView');
    
// });
Route::prefix('chat')->group(function() {
	Route::get('/messages_view', 'UserController@chatMessagesView')->name('c.chat.messages');
	Route::get('/requests_view', 'UserController@chatRequestsView')->name('c.chat.requests');
	Route::put('/update_message', 'UserController@updateChatMessage')->name('c.chat.message.update');
	Route::delete('/delete_message', 'UserController@deleteChatMessage')->name('c.chat.message.delete');
	Route::get('/can_chat_with_view', 'UserController@canChatWithView')->name('c.chat.can_chat_with');
	Route::put('/can_chat_with', 'UserController@canChatWith')->name('c.chat.ccw.submit');
	Route::get('/customer_care_app_config_view', 'UserController@ccAppConfigView')->name('c.chat.ccac.view');
	Route::put('/cc_app_config', 'UserController@ccAppConfig')->name('c.chat.ccac.submit');
	Route::get('/chat_page', 'UserController@chatPage')->name('c.chat.page');

	Route::post('/request_token', 'UserController@requestToken');
	Route::put('/save_resource_id', 'UserController@saveChatResourceId');
	Route::post('/my_chats', 'UserController@getMyChats');
    Route::post('/messages', 'UserController@getMessages');
	Route::post('/start_chat', 'UserController@saveNullMessage');
	Route::post('/waiting_chats', 'UserController@getWaitingChats');
	Route::delete('/delete_chat_request', 'UserController@deleteChatRequest');
	Route::put('/pick_chat', 'UserController@pickWaitingChat');
	Route::post('/save_message', 'UserController@saveChatMessage');
	Route::put('/message_status', 'UserController@updateMessageStatus');
});
//====================End of Push Notification Routes=============

//====================Docs Routes====================
Route::prefix('docs')->group(function() {
	Route::get('/', 'GuestController@routeMap')->name('c.docs.routemap');
	Route::get('/apps', 'GuestController@apps')->name('c.docs.apps');
	Route::get('/licenses', 'GuestController@licenses')->name('c.docs.licenses');
	Route::get('/sessions', 'GuestController@sessions')->name('c.docs.sessions');
	Route::get('/auth', 'GuestController@auth')->name('c.docs.auth');
	Route::get('/tables', 'GuestController@tables')->name('c.docs.tables');
	Route::get('/files', 'GuestController@files')->name('c.docs.files');
	Route::get('/emails', 'GuestController@emails')->name('c.docs.emails');
	Route::get('/chat', 'GuestController@chat')->name('c.docs.chat');
	Route::get('/alerts', 'GuestController@alerts')->name('c.docs.alerts');
	Route::get('/push-notifications', 'GuestController@pushNotifications')->name('c.docs.push');
	Route::get('/prebuilt-applications', 'GuestController@prebuilt')->name('c.docs.prebuilt');
});
//====================End of Files Routes=============

Route::fallback(function(){
    return view('cb.user_interaction')->with(['msg'=>404]);
});