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
    return view('welcome');
});
Route::get('/honeyweb-domain-verification', function () {
    // return view('welcome');
    return "$2y$10$"."vojQmIpHyZkLSpEFc01/bOaHLdnAKv2INhi.JU8O3NdnEJtmUjI6a";
});
// Route::get('/', 'BS\HomeController@homeView');
// -------------------temp routes---------------
Route::get('/up', 'TempController@up');
Route::get('/down', 'TempController@down');
Route::get('/upd', 'TempController@upd');
Route::get('/csv', 'TempController@upd');

Route::post('/theme', 'GuestController@setTheme')->name("theme");

//====================Sraping Routes====================
Route::prefix('crone')->group(function() {
	Route::get('/change_auth_providers_josn_structure', 'UserController@change_auth_providers_josn_structure');
});
//====================End of License Routes=============

//====================Sraping Routes====================
Route::prefix('scrap')->group(function() {
	Route::get('/ship-directory', 'ScrapingController@shipDirectory');
	Route::get('/who-is', 'ScrapingController@whoIsData');
	Route::get('/fill_bark_urls_cat_id', 'ScrapingController@fill_bark_urls_cat_id');
	Route::get('/get_q_n_a_from_bark_dom_com', 'ScrapingController@get_q_n_a_from_bark_dom_com');
	Route::get('/get_all_from_yell_dot_com', 'ScrapingController@get_all_from_yell_dot_com');
	Route::get('/get_reviews_from_yell_dot_com', 'ScrapingController@get_reviews_from_yell_dot_com');
	Route::get('/gtin_series_generator', 'ScrapingController@gtin_series_generator');
});
//====================End of License Routes=============

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

//====================App Routes====================
Route::prefix('app')->group(function() {
	Route::post('/new-app', 'UserController@createNewApp')->name('c.create.new.app');
	Route::get('/app-list', 'UserController@appListView')->name('c.app.list.view');
	Route::get('/app-origins/{id}', 'UserController@appOriginsView')->name('c.app.origins.view');
	Route::post('/new-origin/{id}', 'UserController@addNewOrigin')->name('c.app.new.origin.submit');
	Route::post('/delete-origin/{id}', 'UserController@deleteOrigin')->name('c.app.delete.origin');
	Route::post('/activate', 'UserController@appActivate')->name('c.app.activate');
	Route::get('/app-roles/{id}', 'UserController@appRolesView')->name('c.app.roles.view');
	Route::post('/save-roles/{id}', 'UserController@saveRoles')->name('c.app.roles.save');
	Route::get('/app-permissions/{id}', 'UserController@appPermissionsView')->name('c.app.permissions.view');
	Route::post('/save-permissions/{id}', 'UserController@savePermissions')->name('c.app.permissions.save');
	Route::get('/app-filters/{id}', 'UserController@appFiltersView')->name('c.app.filters.view');
	Route::post('/save-filters/{id}', 'UserController@saveFilters')->name('c.app.filters.save');
	Route::post('/update', 'UserController@updateApp')->name('c.update.app');
	Route::post('/delete/{id}', 'UserController@deleteApp')->name('c.delete.app');
	Route::get('/csv', 'UserController@exportAppsToCSV')->name('c.app.csv.export');
});
//====================End of App Routes=============

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
	Route::post('/upload_file', 'UserController@uploadFile')->name('c.files.upload.file');
	Route::post('/upload_files', 'UserController@uploadFiles')->name('c.files.upload.files');
	Route::get('/{pivot_table}/{pivot_field}/{pivot_id}/{sr_no?}', 'UserController@downloadFile')->name('c.files.download');
	Route::post('/replace-file', 'UserController@replaceFile')->name('c.files.replace');
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

// -------------------routes for Cloud------------------
Route::get('/', 'GuestController@homeView')->name('c.welcome');
// Route::get('/docs', 'GuestController@docsView')->name('c.docs');
Route::get('/login-form', 'GuestController@loginView')->name('c.auth.login');
Route::get('/signup-form', 'GuestController@signupView')->name('c.auth.signup');
Route::get('/password-reset-request-form', 'GuestController@passwordResetRequestFormView')->name('c.auth.password.reset.request');
Route::post('/password-reset-request', 'GuestController@passwordResetRequest')->name('c.auth.password.reset.request.submit');
Route::get('/password-reset-form/{rtype}/{id}', 'GuestController@passwordResetFormView')->name('c.auth.password.reset');
Route::post('/password-reset/{rtype}/{id}', 'GuestController@passwordReset')->name('c.auth.password.reset.submit');


Route::prefix('database')->group(function() {
	Route::get('/create-new-table', 'UserController@createNewTableView')->name('c.create.new.table');
	Route::get('/modify-table', 'UserController@modifyTableView')->name('c.modify.table');
	Route::get('/manual-crud-table', 'UserController@manualCrudTableView')->name('c.manual.crud.table');
	Route::get('/my-table-list', 'UserController@myTableListView')->name('c.my.table.list');
});

Route::prefix('themes')->group(function() {
	Route::get('/new-theme', 'UserController@newThemeView')->name('c.new.theme');
	Route::get('/modify-theme', 'UserController@modifyThemeView')->name('c.modify.theme');
	Route::get('/public-themes', 'UserController@publicThemesView')->name('c.public.themes');
	Route::get('/my-themes', 'UserController@myThemesView')->name('c.my.themes');
	Route::get('/public-files', 'UserController@publicFilesView')->name('c.public.files');
	Route::get('/my-files', 'UserController@myFilesView')->name('c.my.files');
	Route::get('/statistics', 'UserController@statisticsView')->name('c.statistics');
});

Route::prefix('notification')->group(function() {
	Route::get('/mail-notification', 'UserController@emailNotificationView')->name('c.mail.notification');
	Route::get('/settings', 'UserController@notificationSettingsView')->name('c.notification.settings');
});

Route::prefix('real-time')->group(function() {
	Route::get('/my-chat', 'UserController@myChatView')->name('c.my.chat');
	Route::get('/group-chat', 'UserController@groupChatView')->name('c.group.chat');
	Route::get('/email-solutions', 'UserController@emailSolutionsView')->name('c.email.solutions');
});

Route::prefix('obfuscator')->group(function() {
	Route::get('/vba', 'UserController@vbaObfuView')->name('c.obfu.vba');
	Route::get('/group-chat', 'UserController@groupChatView')->name('c.group.chat');
	Route::get('/email-solutions', 'UserController@emailSolutionsView')->name('c.email.solutions');
});

Route::prefix('admin1234536')->group(function() {
	Route::get('/', 'GuestController@adminLoginRedirect');
	Route::get('/login-form', 'GuestController@adminLoginView')->name('c.auth.admin.login');
	Route::post('login', 'Auth\LoginController@adminLogin')->name('c.auth.admin.login.submit');
	Route::get('/signup-form', 'GuestController@adminSignupView')->name('c.auth.admin.signup');
	Route::post('signup', 'Auth\RegisterController@adminRegister')->name('c.auth.admin.signup.submit');
});

Route::prefix('admin')->group(function() {
	Route::get('/', 'AdminController@adminIndex')->name('c.admin.dashboard');

	Route::get('/daily-logs', 'AdminController@dailyLogsView')->name('c.admin.daily.logs');
	Route::get('/visitors', 'AdminController@visitorsView')->name('c.admin.visitors');

	Route::prefix('database')->group(function() {
		Route::get('/create-new-table', 'AdminController@createNewTableView')->name('c.admin.create.new.table');
		Route::get('/modify-table', 'AdminController@modifyTableView')->name('c.admin.modify.table');
		Route::get('/manual-crud-table', 'AdminController@manualCrudTableView')->name('c.admin.manual.crud.table');
		Route::get('/my-table-list', 'AdminController@myTableListView')->name('c.admin.my.table.list');
	});

	Route::prefix('themes')->group(function() {
		Route::get('/new-theme', 'AdminController@newThemeView')->name('c.admin.new.theme');
		Route::get('/modify-theme', 'AdminController@modifyThemeView')->name('c.admin.modify.theme');
		Route::get('/public-themes', 'AdminController@publicThemesView')->name('c.admin.public.admin.themes');
		Route::get('/my-themes', 'AdminController@myThemesView')->name('c.admin.my.themes');
		Route::get('/public-files', 'AdminController@publicFilesView')->name('c.admin.public.admin.files');
		Route::get('/my-files', 'AdminController@myFilesView')->name('c.admin.my.files');
		Route::get('/statistics', 'AdminController@statisticsView')->name('c.admin.statistics');
	});

	Route::prefix('notification')->group(function() {
		Route::get('/mail-notification', 'AdminController@emailNotificationView')->name('c.admin.mail.notification');
		Route::get('/settings', 'AdminController@notificationSettingsView')->name('c.admin.notification.settings');
	});

	Route::prefix('real-time')->group(function() {
		Route::get('/my-chat', 'AdminController@myChatView')->name('c.admin.my.chat');
		Route::get('/group-chat', 'AdminController@groupChatView')->name('c.admin.group.chat');
		Route::get('/email-solutions', 'AdminController@emailSolutionsView')->name('c.admin.email.solutions');
	});
});
// -------------------routes for portfolio------------------
// Route::get('/', 'FController@welcome');

// -------------------worked---------------------

Route::get('registration-form/{rtype}', 'Auth\RegisterController@showRegistrationForm');
Route::post('register/{rtype}', 'Auth\RegisterController@register');
Route::get('email_verification_sent', 'Auth\RegisterController@email_verification_sent');
Route::get('email_verified/{rtype}/{id}', 'Auth\RegisterController@email_verified');
Route::post('login/{rtype}', 'Auth\LoginController@login');
Auth::routes();

Route::get('login/github', 'Auth\LoginController@redirectToGithub');
Route::get('login/github/callback', 'Auth\LoginController@handleGithubCallback');
Route::get('login/google', 'Auth\LoginController@redirectToGoogle');
Route::get('login/google/callback', 'Auth\LoginController@handleGoogleCallback');
Route::get('login/facebook', 'Auth\LoginController@redirectToFacebook');
Route::get('login/facebook/callback', 'Auth\LoginController@handleFacebookCallback');
Route::get('login/twitter', 'Auth\LoginController@redirectToTwitter');
Route::get('login/twitter/callback', 'Auth\LoginController@handleTwitterCallback');
Route::get('login/linkedin', 'Auth\LoginController@redirectToLinkedIn');
Route::get('login/linkedin/callback', 'Auth\LoginController@handleLinkedInCallback');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/contactb', 'MailController@ContactMessage')->name('contact');

Route::get('/test/{table}', 'TTA\HomeController@test');

// Route::get('/{table}/all', 'TTA\HomeController@index');
// Route::get('/{table}/{id}', 'TTA\HomeController@getRecord');
// Route::post('/{table}/new', 'TTA\HomeController@storeRecord');
// Route::put('/{table}/{id}', 'TTA\HomeController@updateRecord');
// Route::delete('/{table}/{id}', 'TTA\HomeController@deleteRecord');
// Route::post('/image_store', 'TTA\HomeController@storeImage');

Route::get('/passport_clients', 'HomeController@passportClients');
Route::get('/passport_authorize_clients/{id}', 'HomeController@passportAuthorizeClients');

