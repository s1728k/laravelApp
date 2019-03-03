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
    return "$2y$10$"."vojQmIpHyZkLSpEFc01/bOaHLdnAKv2INhi.JU8O3NdnEJtmUjI6a";
});
//====================End of Site Verification Routes=============

//====================Guest Routes====================
Route::post('/theme', 'GuestController@setTheme')->name("theme");
Route::get('/', 'GuestController@homeView')->name('c.welcome');
// Route::get('/docs', 'GuestController@docsView')->name('c.docs');
Route::get('/login-form', 'GuestController@loginView')->name('c.auth.login');
Route::get('/signup-form', 'GuestController@signupView')->name('c.auth.signup');
Route::get('/password-reset-request-form', 'GuestController@passwordResetRequestFormView')->name('c.auth.password.reset.request');
Route::post('/password-reset-request', 'GuestController@passwordResetRequest')->name('c.auth.password.reset.request.submit');
Route::get('/password-reset-form/{rtype}/{id}', 'GuestController@passwordResetFormView')->name('c.auth.password.reset');
Route::post('/password-reset/{rtype}/{id}', 'GuestController@passwordReset')->name('c.auth.password.reset.submit');
Auth::routes();
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
	Route::post('/delete-record', 'UserController@deleteRecord')->name('c.db.delete.record');
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
	Route::delete('/delete/{id}', 'UserController@deleteQuery')->name('c.delete.query');
});
//====================End of License Routes=============

//====================Email Routes====================
Route::prefix('email')->group(function() {
	Route::get('/new-domain-view', 'UserController@addNewDomainView')->name('c.email.new.domain.view');
	Route::post('/new-domain', 'UserController@addNewDomain')->name('c.email.new.domain.submit');
	Route::get('/verify-new-domain-view/{id}', 'UserController@verifyNewDomainView')->name('c.email.verify.domain.view');
	Route::post('/get-txt', 'UserController@getTxtRecord')->name('c.email.get.txt');
	Route::post('/get-page', 'UserController@getPageContents')->name('c.email.get.page');
	Route::get('/email-users-list', 'UserController@emailListView')->name('c.email.list.view');
	Route::post('/new-email-account', 'UserController@addNewUser')->name('c.email.new.user.submit');
	Route::post('/delete', 'UserController@deleteEmailAccount')->name('c.email.delete.user');
});
//====================End of Email Routes=============

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

//====================Push Notification Routes====================
Route::prefix('push')->group(function() {
	Route::post('/save-subscription', 'GuestController@saveSubscription')->name('c.push.save_subscription');
	Route::get('/test-message', 'GuestController@sendMessage')->name('c.push.test_message');
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
