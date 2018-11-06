@extends("cb.layouts.docs")

@section("docs")
<h3>Tables</h3>
<hr>
<h4 id="create_new_table">Create New Table<a href="docs/tables/#create_new_table_s"> ↻</a></h4>
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
<hr>
<h4 id="add_fields">Add Field<a href="docs/tables/#add_fields_s"> ↻</a></h4>
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
<hr>
<h4 id="rename_field">Rename Field<a href="docs/tables/#rename_field_s"> ↻</a></h4>
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
<hr>
<h4 id="delete_field">Delete Field<a href="docs/tables/#delete_field_s"> ↻</a></h4>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
	</div>
</div>
<hr>
<h4 id="add_index">Add Index<a href="docs/tables/#add_index_s"> ↻</a></h4>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
	</div>
</div>
<hr>
<h4 id="remove_index">Remove Index<a href="docs/tables/#remove_index_s"> ↻</a></h4>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
	</div>
</div>
<hr>
<h4 id="crud">CRUD (Crate Read Update Delete)<a href="docs/tables/#crud_s"> ↻</a></h4>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>The typical crud view looks like below. Add New Record button and Edit link will take you to new screen. The heading row has input field to take search values</p>
		<div class="parent" style="width: 100;">
		<img src="public/images/d7.png" alt="image" style="display: block; width: 100%; height: auto;" />
		</div>
	</div>
</div>
<hr>
<h4 id="rename_table">Rename Table<a href="docs/tables/#rename_table_s"> ↻</a></h4>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
	</div>
</div>
<hr>
<h4 id="truncate_table">Truncate Table<a href="docs/tables/#truncate_table_s"> ↻</a></h4>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
	</div>
</div>
<hr>
<h4 id="delete_table">Delete Table<a href="docs/tables/#delete_table_s"> ↻</a></h4>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>Steps for Delete Field, Add Index, Remove Index, Rename Table, Truncate Table, Delete Table will be similar to Rename Field</p>
	</div>
</div>
<hr>
<h4 id="export_table">Export Table<a href="docs/tables/#export_table_s"> ↻</a></h4>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>Clicking on the export table CSV or JSON link will download the CSV or JSON file as choosen containing whole table data.</p>
	</div>
</div>
<hr>
<h4 id="import_create">Import - Create<a href="docs/tables/#import_create_s"> ↻</a></h4>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>Click CSV or JSON link under Import - Create to choose file and upload data in mass to table. Ensure that first row in the csv has keys required for table and subsequent rows the values. Also ensure the data types are correct.</p>
	</div>
</div>
<hr>
<h4 id="import_update">Import - Update<a href="docs/tables/#import_update_s"> ↻</a></h4>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>Click CSV or JSON link under Import - Update to choose file and upload data to be updated in mass to table. Ensure that first row in the csv has keys required for table and subsequent rows the values. And there should be column with name id to update record for. Also ensure the data types are correct.</p>
	</div>
</div>
<hr>
<h4 id="api_calls_for_tables">Api Calls<a href="docs/tables/#api_calls_for_tables_s"> ↻</a></h4>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<p>All your api calls should start with <strong>base_url: https://honeyweb.org/api/{your_app_id}/{auth_provider}</strong></p>
		<p>Also use header <strong>'Content-Type': 'application/x-www-form-urlencoded'</strong></p><br>
		
		<p>Create A New Record In The Table</p>
		<p>Url: <strong>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/new</strong>.</p>
		<p>Post Body: <strong>{"_token":"session_token", your table keys and values follows}</strong></p>
		<p>Response on success: <strong>{"_token":"session_token", "status":"success"}</strong></p>
		<p>Response on validation fails: <strong>{object with validation errors}</strong></p>
		<br>

		<p>GET All Records In The Table</p>
		<p>Url: <strong>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/all?_token={session token}</strong>.</p>
		<p>Response: <strong>{"status":"success", "data":[your data in the form of json array], "_token":"A Hash session token"}</strong></p><br>

		<p>GET Single Record In The Table</p>
		<p>Url: <strong>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/{id}?_token={session token}</strong>.</p>
		<p>Response: <strong>{"status":"success", "_token":"A Hash session token", "data":{your single record json object}}</strong></p><br>

		<p>Update an Existing Record</p>
		<p>Url: <strong>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/{id}</strong>.</p>
		<p>Post Body: <strong>{"_token":"session_token", your table keys and values follows}</strong></p>
		<p>Response on success: <strong>{"_token":"session_token", "status":"success"}</strong></p>
		<p>Response on validation fails: <strong>{object with validation errors}</strong></p><br>

		<p>Delete an Existing Record</p>
		<p>Url: <strong>https://honeyweb.org/api/{your_app_id}/{auth_provider}/{table_name}/{id}</strong>.</p>
		<p>Response on success: <strong>{"_token":"session_token", "status":"success"}</strong></p><br>
	</div>
</div>
@endsection