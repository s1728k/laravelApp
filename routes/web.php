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

Route::view('/', 'cb.welcome');
Route::get('/api', function(){
	return response()->json([
		'message' => 'Page Not Found. If error persists, contact info@honeyweb.org'
	]);
}); // api error route
Route::get('/up', 'TempController@up'); // temp route

//====================Site Verification Routes====================
Route::get('/honeyweb-domain-verification', function () {
    return "$2y$10$uyohYtWlS/h598L7/FRXl.I8L6tMlfEONA4GZOc2Gz4Skk21rFJZy";
});
//====================End of Site Verification Routes=============


//====================Authentication Routes====================
Route::get('email_verified/{id}', 'Auth\RegisterController@email_verified');
Route::post('resend_verification_mail', 'Auth\RegisterController@resend_verification_mail');
Auth::routes();
//====================Authentication Routes Ends====================

//====================Guest Routes====================
Route::post('/theme', 'GuestController@setTheme')->name("theme");
Route::get('/', 'GuestController@homeView')->name('c.welcome');
// Route::get('/docs', 'GuestController@docsView')->name('c.docs');
//====================End of Guest Routes=============

//====================User Routes====================
Route::prefix('user')->group(function() {
	Route::post('/add-avatar', 'UserController@addAvatar')->name('c.user.avatar');
	Route::post('/invite-friend', 'UserController@inviteFriend')->name('c.invite.friend');
	Route::get('/usage_report', 'UserController@usageReportView')->name('c.user.usage_report.view');
	Route::get('/recharge_offers', 'UserController@rechargeOffersView')->name('c.user.recharge_offers.view');
	Route::post('/recharge', 'UserController@recharge')->name('c.user.recharge');
	Route::post('/payment/status', 'UserController@paymentCallback')->name('c.payment.callback');
	Route::get('/payment/status/{id}', 'UserController@statusCheck')->name('c.recharge.status')->where(['id'=>'[0-9]+']);
	Route::get('/payment/refund/{id}', 'UserController@refund')->name('c.refund.payment')->where(['id'=>'[0-9]+']);
	Route::get('/payment/refund_status/{id}', 'UserController@refundStatus')->name('c.refund.status')->where(['id'=>'[0-9]+']);
	Route::get('/recharge_history', 'UserController@rechargeHistoryView')->name('c.user.recharge_history.view');
});
//====================End of App Routes=============

//====================App Routes====================
Route::prefix('app')->group(function() {
	Route::post('/new-app', 'UserController@createNewApp')->name('c.create.new.app');
	Route::get('/app-list', 'UserController@appListView')->name('c.app.list.view');
	Route::get('/invited-app-list', 'UserController@invitedAppListView')->name('c.invited.app.list.view');
	Route::get('/public-app-list', 'UserController@publicAppListView')->name('c.public.app.list.view');
	Route::get('/app-description/{id}', 'UserController@appDescView')->name('c.app.desc.view')->where(['id'=>'[0-9]+']);
	Route::post('/copy-app', 'UserController@copyApp')->name('c.app.copy');
	Route::delete('/delete-app', 'UserController@deleteApp')->name('c.app.delete');
	Route::post('/save-app-description', 'UserController@saveAppDesc')->name('c.app.desc.submit');
	Route::get('/app-user-name-fields/{id}', 'UserController@appUserNameFieldsView')->name('c.app.user.name.fields.view')->where(['id'=>'[0-9]+']);
	Route::post('/save-user-name-fields', 'UserController@saveUserNameFields')->name('c.app.user.name.fields.save');
	Route::get('/app-origins/{id}', 'UserController@appOriginsView')->name('c.app.origins.view')->where(['id'=>'[0-9]+']);
	Route::post('/new-origin/{id}', 'UserController@addNewOrigin')->name('c.app.new.origin.submit')->where(['id'=>'[0-9]+']);
	Route::delete('/delete-origin/{id}', 'UserController@deleteOrigin')->name('c.app.delete.origin')->where(['id'=>'[0-9]+']);
	Route::get('/invited-users/{id}', 'UserController@invitedUsersView')->name('c.invited.users.view')->where(['id'=>'[0-9]+']);
	Route::post('/new-invited-user', 'UserController@inviteNewUser')->name('c.invited.new.user.submit');
	Route::delete('/delete-invited-user', 'UserController@deleteInvitedUser')->name('c.invited.delete.user');
	Route::post('/activate', 'UserController@appActivate')->name('c.app.activate');
	Route::post('/update', 'UserController@updateApp')->name('c.update.app');
	Route::delete('/delete/{id}', 'UserController@deleteApp')->name('c.delete.app')->where(['id'=>'[0-9]+']);
	Route::get('/sql/{id?}', 'UserController@exportDb')->name('c.app.sql.export');
	Route::get('/csv', 'UserController@exportAppsToCSV')->name('c.app.csv.export');

	Route::get('/log_view', 'UserController@logView')->name('c.app.log');
});
//====================End of App Routes=============

//====================License Routes====================
Route::prefix('license')->group(function() {
	Route::post('/new-license', 'UserController@createNewLicense')->name('l.create.new.license');
	Route::get('/license-list', 'UserController@licenseListView')->name('l.license.list.view');
	Route::get('/license-details/{id}', 'UserController@licenseDetailsView')->name('l.license.details.view')->where(['id'=>'[0-9]+']);
	Route::get('/test-bench', 'UserController@testBenchView')->name('l.test.bench.view');
	Route::post('/update/{id}', 'UserController@updateLicense')->name('l.update.license')->where(['id'=>'[0-9]+']);
	Route::post('/delete/{id}', 'UserController@deleteLicense')->name('l.delete.license')->where(['id'=>'[0-9]+']);
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
	Route::get('/query-details/{id}', 'UserController@queryDetailsView')->name('c.query.details.view')->where(['id'=>'[0-9]+']);
	Route::put('/update/{id}', 'UserController@updateQuery')->name('c.update.query')->where(['id'=>'[0-9]+']);
	Route::delete('/delete', 'UserController@deleteQuery')->name('c.delete.query');
	Route::get('/custom-valid-msg-view', 'UserController@customValidMsgView')->name('c.query.valid.msg.view');
	Route::post('/custom-valid-msg-submit', 'UserController@customValidMsg')->name('c.query.valid.msg.submit');
	// Route::get('/custom-valid-rules-view', 'UserController@customValidRulesView')->name('c.query.valid.rules.view');
	Route::get('/custom-valid-view', 'UserController@customValidView')->name('c.query.valid.view');
	Route::post('/custom-valid-submit', 'UserController@customValid')->name('c.query.valid.submit');
	Route::delete('/custom-valid-delete', 'UserController@deleteCustomValid')->name('c.query.valid.delete');
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
	Route::get('/{id}', 'UserController@downloadFile')->name('c.files.download')->where(['id'=>'[0-9]+']);
	Route::post('/replace-file', 'UserController@replaceFile')->name('c.files.replace');
	Route::post('/delete-file', 'UserController@deleteFile')->name('c.files.delete');
});
//====================End of Files Routes=============

//====================Email Routes====================
Route::prefix('email')->group(function() {
	Route::get('/email-users-list', 'UserController@emailListView')->name('c.email.list.view');

	Route::get('/domain-list', 'UserController@domainListView')->name('c.domain.list.view');
	Route::post('/domain-new', 'UserController@addNewDomain')->name('c.domain.add.new');
	Route::get('/domain-verify/{id}', 'UserController@verifyNewDomainView')->name('c.domain.verify')->where(['id'=>'[0-9]+']);
	Route::delete('/domain-delete', 'UserController@deleteDomain')->name('c.domain.delete');

	Route::get('/alias-list', 'UserController@aliasListView')->name('c.alias.list.view');
	Route::post('/alias-new', 'UserController@addNewAlias')->name('c.alias.add.new');
	Route::post('/alias-verify', 'UserController@verifyAlias')->name('c.alias.verify');
	Route::delete('/alias-delete', 'UserController@deleteAlias')->name('c.alias.delete');

	Route::get('/mail-list', 'UserController@mailListView')->name('c.mail.list.view');
	Route::get('/new-mail-view', 'UserController@addNewMailView')->name('c.mail.add.new.view');
	Route::get('/update-mail-view/{id}', 'UserController@updateMailView')->name('c.mail.update.view')->where(['id'=>'[0-9]+']);
	Route::post('/mail-new', 'UserController@addNewMail')->name('c.mail.add.new');
	Route::post('/mail-send', 'UserController@sendMail')->name('c.mail.send');
	Route::put('/mail-update', 'UserController@updateMail')->name('c.mail.update');
	Route::post('/mail-copy', 'UserController@copyMail')->name('c.mail.copy');
	Route::delete('/mail-delete', 'UserController@deleteMail')->name('c.mail.delete');

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
	Route::get('/messages', 'UserController@pushMessageList')->name('c.push.messages');
	Route::get('/new_message', 'UserController@createMessageView')->name('c.push.new.msg.view');
	Route::post('/new_message_submit', 'UserController@createMessage')->name('c.push.new.msg.submit');
	Route::get('/update_message/{id}', 'UserController@updateMessageView')->name('c.push.update.msg')->where(['id'=>'[0-9]+']);
	Route::put('/update_message_submit', 'UserController@updateMessage')->name('c.push.update.msg.submit');
	Route::post('/copy_message', 'UserController@copyMessage')->name('c.push.copy.msg');
	Route::post('/delete_message', 'UserController@deleteMessage')->name('c.push.del.msg');
	Route::get('/broadcast/{id}', 'UserController@broadcast')->name('c.push.broadcast')->where(['id'=>'[0-9]+']);
	Route::get('/push_subscriptions', 'UserController@pushSubscriptionList')->name('c.push.subscriptions');
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
	Route::get('/', 'GuestController@indexHome')->name('c.docs.home');
	Route::get('/routemap', 'GuestController@routeMap')->name('c.docs.routemap');
	Route::get('/{index}', 'GuestController@docIndex')->name('c.docs.index');
	Route::get('/{index}/{sub_index}', 'GuestController@docArticle')->name('c.docs.article');
	// Route::get('/apps', 'GuestController@apps')->name('c.docs.apps');
	// Route::get('/licenses', 'GuestController@licenses')->name('c.docs.licenses');
	// Route::get('/sessions', 'GuestController@sessions')->name('c.docs.sessions');
	// Route::get('/auth', 'GuestController@auth')->name('c.docs.auth');
	// Route::get('/tables', 'GuestController@tables')->name('c.docs.tables');
	// Route::get('/files', 'GuestController@files')->name('c.docs.files');
	// Route::get('/emails', 'GuestController@emails')->name('c.docs.emails');
	// Route::get('/chat', 'GuestController@chat')->name('c.docs.chat');
	// Route::get('/alerts', 'GuestController@alerts')->name('c.docs.alerts');
	// Route::get('/push-notifications', 'GuestController@pushNotifications')->name('c.docs.push');
	// Route::get('/prebuilt-applications', 'GuestController@prebuilt')->name('c.docs.prebuilt');
});
//====================End of Files Routes=============

//====================Docs Routes====================
Route::prefix('blog')->group(function() {
	Route::get('/', 'GuestController@categoryHome')->name('c.blog.home');
	Route::get('/{category}', 'GuestController@blogCategory')->name('c.blog.category');
	Route::get('/{category}/{sub_category}', 'GuestController@blogArticle')->name('c.blog.article');
});
//====================End of Files Routes=============

Route::fallback(function(){
    return view('cb.user_interaction')->with(['msg'=>404]);
});