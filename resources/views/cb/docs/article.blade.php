@extends("cb.layouts.docs")

@if($index == 'my_apps' && $article =='token_lifetoken')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/token_lifetoken">Token Lifetime</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Token Lifetime</h1> 
	<p>This is the lifetime of session _token in seconds. You can set this time in <a href="/docs/my_apps/update_app">update</a> action link. Default session _token lifetime is 43200 seconds.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='availability')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/availability">Availability</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Availability</h1> 
	<p>This field can be either private or public. Private app can be seen only by you and users whom you have invited to update the app. Public apps are those which can be seen by any user. You can make your app Public or Private in <a href="/docs/my_apps/update_app">update</a> action link.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='activate_app_id')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/activate_app_id">Active App Id</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Active App Id</h1> 
	<p>This is the ID of active app you last modified. This is stored in the backend. Whenever you login the app with this active app id is opened for modification. Before you make any modification make sure you are modifying for the correct app. This id is displayed on title section of every page. To change the active app click <a href="/docs/my_apps/activate_app">activate</a> action link.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='invited_apps')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/invited_apps">Invited Apps</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Invited Apps</h1> 
	<p>Invited apps are the list of apps created by other users and you have been invited to update the app.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='public_apps')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/public_apps">Public Apps</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Public Apps</h1> 
	<p>Public apps are the list of apps created by you or other users and made public to make it available to every user.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='create_new_app')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/create_new_app">Create New App</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Create New App</h1> 
	<p>Press Create New App button that will prompt you for app name. Enter the reasonable name of the app and press ok. The backend creates app with unique app id and app secret. Backend also creates users authenticatable table when you create an app. Alteast one authenticatable table is required to access data from this backend. You can have multiple authenticatable tables for a single app.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='activate_app')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/activate_app">Activate App</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Activate App</h1> 
	<p>Click this action link to activate the app when you wish do any modification to that perticular app. This last activated app remain active until you activate another app.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='update_app')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/update_app">Update App</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Update App</h1> 
	<p>Click this action link to edit app name, <a href="/docs/my_apps/token_lifetoken">token lifetime</a>, <a href="/docs/my_apps/availability">availability</a> and refresh app secret.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='user_fields')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/user_fields">User Fields</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>User Fields</h1> 
	<p>This action link shall take you to new screen where you can set user name fields for auth provider tables for the corresponding app. User name fields are mandatory and unique fields and are used for checking the user name when you login. Default user name field is email. You can change this to phone, username etc.,</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='origins')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/origins">Origins</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Origins</h1> 
	<p>This action link shall take you to new screen where you can add and remove the origin of requests. For websites this shall be <i>https://example.com</i>. For requests from server add ip address. For requests from mobile you can set it any key and set origin header from mobile application.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='invited_users')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/invited_users">Invited Users</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Invited Users</h1> 
	<p>This action link shall take you to new screen with list of invited users and their email addresses. Click Invite New User button and enter email address of new user whom you want to invite and press invite. If email address is not available in our database then invitation link to join our website will be sent. When the user with this email address logs in, the user sees you app in the list of invited apps. You can delete the invited user later from this screen.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='export_db')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/export_db">ExportDB</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>ExportDB</h1> 
	<p>Click this action link to download your app sql dump export file.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='description')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/description">Description</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Description</h1> 
	<p>This action link shall take you to new screen with markdown editor feature. You may write some description of you app for other users reference here.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='copy')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/copy">Copy</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Copy</h1> 
	<p>This action link shall duplicate the copy of app.</p>
</div>
@endsection

@endif

@if($index == 'my_apps' && $article =='delete')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a> >> <a href="/docs/my_apps/delete">Delete</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Delete</h1> 
	<p>This action link shall delete the copy of app.</p>
</div>
@endsection

@endif





















@if($index == 'licenses' && $article =='create_new_license')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/licenses">Licenses</a> >> <a href="/docs/licenses/create_new_license">Create New License</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Create New License</h1> 
	<p>Select the licenses top nav. Press Create New License button that will prompt you for total no. of licenses and expiry date. Enter the detail to create new license. The total no. of licenses is 1 if the license is given to single user. for server type of licenses or shared licenses this can be more than one.</p>
</div>
@endsection

@endif

@if($index == 'licenses' && $article =='edit_license')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/licenses">Licenses</a> >> <a href="/docs/licenses/edit_license">Edit License</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Edit License</h1> 
	<p>Every license list item will have edit link. Click this link to increase the number of licenses and change the expiry date.</p>
</div>
@endsection

@endif

@if($index == 'licenses' && $article =='license_detail')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/licenses">Licenses</a> >> <a href="/docs/licenses/license_detail">License Detail</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>License Detail</h1> 
	<p>Click the license detail link on every list item to goto new screen with details of licenses usage. This details screen will have manual activate or deactivate option for admin purpose. This screen will display how the number of licenses are being used for shared license system, hardwarecode, computer name and user.</p>
</div>
@endsection

@endif

@if($index == 'licenses' && $article =='activate_license')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/licenses">Licenses</a> >> <a href="/docs/licenses/activate_license">Activate License</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Activate License</h1> 
	<p>You can see this activate link in license details screen to activate the license manually for admin purpose.</p>
</div>
@endsection

@endif

@if($index == 'licenses' && $article =='deactivate_license')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/licenses">Licenses</a> >> <a href="/docs/licenses/deactivate_license">Deactivate License</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Deactivate License</h1> 
	<p>You can see this deactivate link in license details screen to deactivate the license manually for admin purpose.</p>
</div>
@endsection

@endif

@if($index == 'licenses' && $article =='test_bench')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/licenses">Licenses</a> >> <a href="/docs/licenses/test_bench">Test Bench</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Test Bench</h1> 
	<p>Test Bench button on the top right corner of the listing screen will take you to new test bench screen. There simulation of how software sends json to backend for activation and deactivation is demonstrated. Also there is simulation of the how client websites which sells software are required to send to request to this backend to get license keys for purchased user is shown.</p>
</div>
@endsection

@endif





















@if($index == 'tables' && $article =='create_new_table')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/create_new_table">Create New Table</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Create New Table</h1> 
	<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-9">
		<p>STEP 1, 2:</p>
		<p>Go to Tables menu, Press Create New Table Button</p>
		<div class="parent" style="width: 100;">
		<img src="public/images/d1.png" alt="image" style="display: block; width: 100%; height: auto;" />
		</div>
	</div>
	<div class="col-md-3"></div>
</div>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-6">
		<p>STEP 3, 4:</p>
		<p>Enter the number of columns/fields and press enter</p>
		<div class="parent" style="width: 100;">
		<img src="public/images/d2.png" alt="image" style="display: block; width: 100%; height: auto;" />
		</div>
	</div>
	<div class="col-md-6"></div>
</div>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>STEP 5 to 15:</p>
		<p><strong>5:</strong> Enter name of the table, <strong>6:</strong> Select the model for regular table and authenticatable for auth table, <strong>7:</strong> select the primary key 'id' data type options are tinyIncrements, smallIncrements, mediumIncrements, Increments, bigIncrements. On hover on these options you will get the range values for different options. <strong>8:</strong> Select the timestamps which will create 2 fields <i>created_at</i> and <i>updated_at</i>. <strong>9:</strong> Enter field names this column. <strong>10:</strong> Select the data type. <strong>11:</strong> Enter the length of string or options seperated by comma for enum data type or M(total digits), D(decimals) for decimal data type. <strong>12:</strong> Enter the default value, if no value entered then default will be null. <strong>13:</strong> Select the index value, options available are <i>Primary</i>, <i>Unique</i>, <i>Index</i>. <strong>14:</strong> Press Create New Table button. <strong>Troubleshooting:</strong> the form has validation any mistake in the input will get back validation error message guiding you to correct mistake. After the table is created control takes you to listing screen where you see the table being created is listed out there. <strong>Additional Information:</strong> addition or deletion of fields at this stage is not possible. You can add or delete columns in the listing screen. This was to make validation more strong.</p>
		<div class="parent" style="width: 100;">
		<img src="public/images/d3.png" alt="image" style="display: block; width: 100%; height: auto;" />
		</div>
	</div>
</div>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='add_fields')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/add_fields">Add Field</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Add Field</h1> 
	<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-7">
		<p>STEP 1, 2:</p>
		<p>Go to Tables menu, click on Add Fields link</p>
		<div class="parent" style="width: 100;">
		<img src="public/images/d4.png" alt="image" style="display: block; width: 100%; height: auto;" />
		</div>
	</div>
	<div class="col-md-5"></div>
</div>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-6">
		<p>STEP 3, 4:</p>
		<p>Enter the number of columns/fields and press enter</p>
		<div class="parent" style="width: 100;">
		<img src="public/images/d2.png" alt="image" style="display: block; width: 100%; height: auto;" />
		</div>
	</div>
	<div class="col-md-6"></div>
</div>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>STEP 5 to 11:</p>
		<p>For steps from <strong>5 to 9</strong> refer section Create New Table Steps 9 to 13. <strong>10: </strong> Select existing field name after which this column should appear. <strong>11: </strong> Press Update Table button.</p>
		<div class="parent" style="width: 100;">
		<img src="public/images/d5.png" alt="image" style="display: block; width: 100%; height: auto;" />
		</div>
	</div>
</div>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='rename_field')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/rename_field">Rename Field</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Rename Field</h1> 
	<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>STEP 1, 2: remains common for next table actions.</p>
		<p>STEP 3 to 5: </p>
		<p><strong>3: </strong>Select the existing column name. <strong>4: </strong>Enter new column name. <strong>5: </strong>Press rename button.</p>
	</div>
	<div class="col-md-5">
		<div class="parent" style="width: 100;">
		<img src="public/images/d6.png" alt="image" style="display: block; width: 100%; height: auto;" />
		</div>
	</div>
	<div class="col-md-7"></div>
</div>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='delete_field')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/delete_field">Delete Field</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Delete Field</h1> 
	<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
	</div>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='add_index')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/add_index">Add Index</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Add Index</h1> 
	<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='remove_index')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/remove_index">Remove Index</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Remove Index</h1> 
	<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='crud')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/crud">CRUD (Crate Read Update Delete)</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>CRUD (Crate Read Update Delete)</h1> 
	<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>The typical crud view looks like below. Add New Record button and Edit link will take you to new screen. The heading row has input field to take search values</p>
		<div class="parent" style="width: 100;">
		<img src="public/images/d7.png" alt="image" style="display: block; width: 100%; height: auto;" />
		</div>
	</div>
</div>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='rename_table')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/rename_table">Rename Table</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Rename Table</h1> 
	<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='truncate_table')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/truncate_table">Truncate Table</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Truncate Table</h1> 
	<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='delete_tab')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/delete_tab">Delete Table</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Delete Table</h1> 
	<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='export_table')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/export_table">Export Table</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Export Table</h1> 
	<p>Clicking on the export table CSV or JSON link will download the CSV or JSON file as choosen containing whole table data.</p>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='import_create')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/import_create">Import - Create</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Import - Create</h1> 
	<p>Click CSV or JSON link under Import - Create to choose file and upload data in mass to table. Ensure that first row in the csv has keys required for table and subsequent rows the values. Also ensure the data types are correct.</p>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='import_update')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/import_update">Import - Update</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Import - Update</h1> 
	<p>Click CSV or JSON link under Import - Update to choose file and upload data to be updated in mass to table. Ensure that first row in the csv has keys required for table and subsequent rows the values. And there should be column with name id to update record for. Also ensure the data types are correct.</p>
</div>
@endsection

@endif

@if($index == 'tables' && $article =='api_calls_for_tables')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a> >> <a href="/docs/tables/api_calls_for_tables">Api Calls</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Api Calls</h1> 
	<p>For non jquery frameworks use this header for all api calls <pre>'Content-Type': 'application/x-www-form-urlencoded'</pre></p>
	<p>Standard form of url: <pre>https://honeyweb.org/api/{query_id}</pre></p><br>
	
	<h2>Create A New Record In The Table</h2>
	<p>Url: <pre>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/new</pre></p>
	<p>Post Body: <pre>{"_token":"session_token", your table keys and values follows}</pre></p>
	<p>Response on success: <pre>{"_token":"session_token", "status":"success"}</pre></p>
	<p>Response on validation fails: <pre>{object with validation errors}</pre></p>
	<br>

	<h2>GET All Records In The Table</h2>
	<p>Url: <pre>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/all?_token={session token}</pre></p>
	<p>Response: <pre>{"status":"success", "data":[your data in the form of json array], "_token":"A Hash session token"}</pre></p><br>

	<h2>GET Single Record In The Table</h2>
	<p>Url: <pre>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/{id}?_token={session token}</pre></p>
	<p>Response: <pre>{"status":"success", "_token":"A Hash session token", "data":{your single record json object}}</pre></p><br>

	<h2>Update an Existing Record</h2>
	<p>Url: <pre>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/{id}</pre></p>
	<p>Post Body: <pre>{"_token":"session_token", your table keys and values follows}</pre></p>
	<p>Response on success: <pre>{"_token":"session_token", "status":"success"}</pre></p>
	<p>Response on validation fails: <pre>{object with validation errors}</pre></p><br>

	<h2>Delete an Existing Record</h2>
	<p>Url: <pre>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/{id}</pre></p>
	<p>Response on success: <pre>{"_token":"session_token", "status":"success"}</pre></p><br>
</div>
@endsection

@endif


























@if($index == 'queries' && $article =='create_new_query')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/queries">Queries</a> >> <a href="/docs/queries/create_new_query">Create New Query</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Create New Query</h1> 
	<p>Query is a record in our database which keeps all the query parameters of your url so you dont have to use any query in your url. You only need to mention the query id in your url. Typical url looks like this.</p> <pre>https://honeyweb.org/api/23</pre> 
	<p>This button command shall take to new screen. To fill the form follow the below guidelines.</p>
	<h2>Query Nick Name</h2>
	<p>This is just a nick name for your rememberance. You can give some reasonalbe name to suite query.</p>
	<h2>Auth Providers</h2>
	<p>Here all the auth providers of your app shall be listed including guest. Select the authors who can use this query. If your app has roles, then create so many auth providers and assign permission to roles in this option. If you select multiple authors per query then you need to mention the author name in the url query otherwise first author in the list shall be considered.</p>
	<pre>https://honeyweb.org/api/23?author=users</pre>
	<h2>Tables</h2>
	<p>Here all the tables of your app shall be listed including auth providers. Select the table you are querying. If you select multiple tables per query then you need to mention the table name in the url query otherwise first table in the list shall be considered.</p>
	<pre>https://honeyweb.org/api/23?table=brands</pre>
	<p>Click the 'Get Table Fields' button in the tables section to list all the fields of selected tables in Fillables, Hiddens, Mandatory sections. </p>
	<h2>Commands</h2>
	<p>Here all the allowed commands for query are listed. Select the command for your query. If you select multiple commands per query then you need to mention the command name in the url query otherwise first command in the list shall be considered.</p>
	<pre>https://honeyweb.org/api/23?command=all</pre>
	<p>Command names shown in options (like 'ReadAll') are conventional only. Command names displayed in selected panel (like 'all') are true names.</p>
	<div class="table-responsive">
	<table class="table table-bordered">
		<caption>List of available commands</caption>
		<thead>
			<tr>
				<th>Command Name</th>
				<th>True Name</th>
				<th>Http Verb</th>
				<th>Description</th>
				<th>Url Params</th>
				<th>Typical Url</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>ReadAll</td>
				<td>all</td>
				<td>get</td>
				<td>reads all the records in the table</td>
				<td>None</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
			<tr>
				<td>Create</td>
				<td>new</td>
				<td>post</td>
				<td>creates new record in the table</td>
				<td>None</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
			<tr>
				<td>Read</td>
				<td>get</td>
				<td>get</td>
				<td>reads single record from the table</td>
				<td>ID</td>
				<td><pre>https://honeyweb.org/api/23/1</pre></td>
			</tr>
			<tr>
				<td>Update</td>
				<td>mod</td>
				<td>put</td>
				<td>updates single record in the table</td>
				<td>ID</td>
				<td><pre>https://honeyweb.org/api/23/1</pre></td>
			</tr>
			<tr>
				<td>Delete</td>
				<td>del</td>
				<td>delete</td>
				<td>deletes single record in the table</td>
				<td>ID</td>
				<td><pre>https://honeyweb.org/api/23/1</pre></td>
			</tr>
			<tr>
				<td>SignUp</td>
				<td>signup</td>
				<td>post</td>
				<td>creates new user in the auth provider table</td>
				<td>none</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
			<tr>
				<td>SendEmailVerificationCode</td>
				<td>sevc</td>
				<td>post</td>
				<td>Sends Verification Mail to you client's email address.</td>
				<td>none</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
			<tr>
				<td>VerifyEmail</td>
				<td>ve</td>
				<td>post</td>
				<td>Verifies the email address registered by your client.</td>
				<td>none</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
			<tr>
				<td>Login</td>
				<td>login</td>
				<td>post</td>
				<td>verifies your client's credentials and returns session _token</td>
				<td>none</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
			<tr>
				<td>ConditionalLogin</td>
				<td>clogin</td>
				<td>post</td>
				<td>verifies your client's credentials and returns session _token only if email is verified.</td>
				<td>none</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
			<tr>
				<td>RefreshToken</td>
				<td>refresh</td>
				<td>post</td>
				<td>gets new session _token value.</td>
				<td>none</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
			<tr>
				<td>FilesUpload</td>
				<td>files_upload</td>
				<td>post</td>
				<td>url for file upload.</td>
				<td>none</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
			<tr>
				<td>SendMail</td>
				<td>mail</td>
				<td>post</td>
				<td>url for sending mail using api request.</td>
				<td>none</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
			<tr>
				<td>PushSubscribe</td>
				<td>ps</td>
				<td>post</td>
				<td>Url for sending push notification.</td>
				<td>none</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
			<tr>
				<td>GetAppSecret</td>
				<td>secret</td>
				<td>post</td>
				<td>gets app secret.</td>
				<td>none</td>
				<td><pre>https://honeyweb.org/api/23</pre></td>
			</tr>
		</tbody>
	</table>
	</div>
	<h2>Fillables</h2>
	<p>Select the fields that are needs to be filled when you create or update the table record. By default all the records are fillable.</p>
	<h2>Hiddens</h2>
	<p>Select the fields that are needs to be hidden when you read the table record/s. By default none of the field is hidden.</p>
	<h2>Mandatory</h2>
	<p>Select the fields that are mandatory when you create or update the table record. By default none of the field is mandatory. You can set this option in validation also.</p>
</div>
@endsection

@endif

@if($index == 'queries' && $article =='jquery')

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/queries">Queries</a> >> <a href="/docs/queries/jquery">Api Calls Examples With JQuery</a></h4>
@endsection

@section("docs")
<div class="jumbotron">
	<h1>Api Calls Examples With JQuery</h1> 
	<p>Standard form of url: <pre>https://honeyweb.org/api/{query_id}</pre></p><br>
	<h2>ReadAll</h2> 
	<pre>
function readAll(){
    $.get("https://honeyweb.org/api/23", function(data, status){
        if(status=='success'){
            console.log(data);
        }
    });
}</pre>
<samp>[{"id":1,"brand":"Apple"},{"id":2,"brand":"Asus"},{"id":3,"brand":"Gionee"},{"id":4,"brand":"HTC"},{"id":5,"brand":"LeEco"},{"id":6,"brand":"Lenovo"},{"id":7,"brand":"LG"},{"id":8,"brand":"Motorola"},{"id":9,"brand":"Nexus"},{"id":10,"brand":"plus"},{"id":11,"brand":"Oppo"},{"id":12,"brand":"Samsung"},{"id":13,"brand":"Sony"},{"id":14,"brand":"Vivo"},{"id":15,"brand":"Xiaomi"},{"id":16,"brand":"Others"}]</samp>
	<h2>Read</h2> 
	<pre>
function readAll(){
    $.get("https://honeyweb.org/api/24/1", function(data, status){
        if(status=='success'){
            console.log(data);
        }
    });
}</pre>
<samp>{"id":1,"brand":"Apple"}</samp>
	
	
	<h2>Create A New Record In The Table</h2>
	<p>Url: <pre>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/new</pre></p>
	<p>Post Body: <pre>{"_token":"session_token", your table keys and values follows}</pre></p>
	<p>Response on success: <pre>{"_token":"session_token", "status":"success"}</pre></p>
	<p>Response on validation fails: <pre>{object with validation errors}</pre></p>
	<br>

	<h2>GET All Records In The Table</h2>
	<p>Url: <pre>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/all?_token={session token}</pre></p>
	<p>Response: <pre>{"status":"success", "data":[your data in the form of json array], "_token":"A Hash session token"}</pre></p><br>

	<h2>GET Single Record In The Table</h2>
	<p>Url: <pre>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/{id}?_token={session token}</pre></p>
	<p>Response: <pre>{"status":"success", "_token":"A Hash session token", "data":{your single record json object}}</pre></p><br>

	<h2>Update an Existing Record</h2>
	<p>Url: <pre>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/{id}</pre></p>
	<p>Post Body: <pre>{"_token":"session_token", your table keys and values follows}</pre></p>
	<p>Response on success: <pre>{"_token":"session_token", "status":"success"}</pre></p>
	<p>Response on validation fails: <pre>{object with validation errors}</pre></p><br>

	<h2>Delete an Existing Record</h2>
	<p>Url: <pre>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/{id}</pre></p>
	<p>Response on success: <pre>{"_token":"session_token", "status":"success"}</pre></p><br>
</div>
@endsection

@endif
