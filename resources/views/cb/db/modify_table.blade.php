@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 text-center">
			<h3>Modify Table</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<form>
				<div class="form-group">
					<label for="table_name">Table Name</label>
					<select id="table_name" name="table_name" class="form-control" onchange="getTableFields()"
					 >
						<option *ngFor="let my_table of my_table_list" value="gdfdg">gfdgd</option>
					</select>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<form>
				<div class="form-group">
					<label for="table_description">Table Description</label>
					<textarea id="table_description" rows="5" name="table_description" class="form-control" ></textarea>
				</div>
				<div class="form-group">
					<label for="keywords">Key Words</label>	
					<input id="keywords" name="keywords" class="form-control" >
					<p>* maximum of 5 comma separated keywords are accepted.</p>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<form>
				<div class="form-group">
					<label for="Authenticatable"><input type="checkbox" id="Authenticatable" name="Authenticatable">Authenticatable</label>
				</div>
			</form>
		</div>
		<div class="col-md-3">
			<form>
				<div class="form-group">
					<label for="notifiable"><input type="checkbox" id="notifiable" name="notifiable">Notifiable</label>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<label for="del_fields">Add the deletable fields to this array.</label>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<div class="input-group">
					<select name="after_field" class="form-control">
						<option  value="gdfg">gfdgdf</option>
					</select>
					<span class="input-group-addon"><button onclick="addDeleteField()">Add</button></span>
					<span class="input-group-addon"><button onclick="removeDeleteField()">Remove</button></span>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				[jgh]
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<label for="del_fields">Add the fields to this array to drop their indexes.</label>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<div class="input-group">
					<select name="after_field" class="form-control">
						<option *ngFor="let field_name of my_table_fields" value="gh">jgh</option>
					</select>
					<span class="input-group-addon"><button onclick="addDeleteFieldIndex()">Add</button></span>
					<span class="input-group-addon"><button onclick="removeDeleteFieldIndex()">Remove</button></span>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				[jgh]
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table">
					<caption>Choose The Table Fields To Add To Table "jhfg" <small>* optional fields</small></caption>
					<thead>
						<tr>
							<th><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFiSURBVGhD7Zk5TgNBEEUnAyJEynoqApBI2S4ACVsEV8DcBMQNSOAuQAa8HyB9jVrqmfbSLaue9JK2q6a+pbGtqS4IgqnYxVt8xbdK6tqaQbMUcY5f+NuImuUMR3GKqWYtqNkGsYPfmGrSgp+4jVnu0At/cIIHuL9gdc0n1Aw+0zVmeUEvesTa6IP0mTRjlnf0oiOsjWbwmTRjlg/0okOsjWbwmTRjllkFWcGLnjoroWqQdfQ+UmclRBAviiDofWQEQe8TQUqIIAkiiBf1g6zixgD30PtInaXe23cNnbkEuUR/fR7eoxNBvCiCTOFCgizNzT6U+B1JEEG8KIKg95ERBL1PBCkhgiSoGkT/AB566qyEqkFmSVGQpXmI3eJaQTsSn+kZs2jx6EVasqhRS4ueK8yi1VtLS9C+Wr1t4SBaXoYe4yhOsLX19OgQ/2h7eoP6Akgt8xehrq17YhODICii6/4AKeYLm3RM7hQAAAAASUVORK5CYII=" onclick="addNewField()"></th>
							<th>Field Name</th>
							<th>Datatype</th>
							<th>Modifier*</th>
							<th>Index*</th>
							<th>Add Field To Array</th>
							<th>Remove</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>
								<div class="form-group">
									<input type="text" name="table_name" class="form-control" />
								</div>
							</td>
							<td>
								<div class="form-group">
									<select name="data_type" class="form-control" >
										<option *ngFor="let data_type of data_types" value="dsf">fsd</option>
									</select>
								</div>
							</td>
							<td>
								<button class="btn btn-primary" onclick="modifierInputDialog(i)">Select Modifiers</button>
								<p *ngIf="selTable['fields'][i]['data_type_modifier']">Modifiers Selected</p>
							</td>
							<td>
								<button class="btn btn-primary" onclick="indexInputDialog(i)">Select Indexes</button>
								<p *ngIf="selTable['fields'][i]['my_sql_index']">Indexes Selected</p>
							</td>
							<td>
								<div class="form-group">
									<select name="elequent_array" class="form-control" >
										<option value="fillable">fillable</option>
										<option value="hidden">hidden</option>
										<option value="both">both</option>
										<option value="none">none</option>
									</select>
								</div>
							</td>
							<td><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAEjSURBVGhD7Zk9agJRFIVnB1bBbWgR12FrbZAUgq21ja7AXkvLtJaWCWQBWYZ2gSTnwBMuj4fj6H0zYTwffM3lvJ8DU70phBDi4XmF+xKZ+ddM4Q/8LZEZZhvhBe4u+AavKXGWWa5J7XWWZ7qzhqkL5ZRnuqMid5ilyBCuapZnijJ6cJLZAczOHKa+Z0+XMDsqUkEVqULrixzhh/ETxhnObIZr4gxttMgBWjowznBm4Zo4Q1WkCioSZhYV8UBFwsyiIh6oSJhZVMQDFQkzi4p4oCJhZlERD1pf5AvaR7YZjDOc2QzXxBnaaBFPW1NkAbPDv0ipwz0dw+x04QmmLuAhn4ieYC2M4DdMXeQeuSf3rpVnuIXv0D663SL32MA+FEK4UxR/dgb0zufBmgUAAAAASUVORK5CYII=" onclick="removeField(i)"></td>
						</tr>
						<tr>
							<td colspan="7"><button class="btn btn-primary" onclick="compositeIndexInputDialog()">Select Composite Indexes (Optional)</button></td>
						</tr>
					</tbody>
				</table>
			</div>
			<button class="btn btn-primary" onclick="updateTable()">Modify Table</button>
		</div>
	</div>
	<div class="row rheight">
		<div class="col-md-12">
			
		</div>
	</div>
</div>



<!-- Modal -->
<div id="createNewTableGroup" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New Table Group</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
			<input type="text" name="input_for_modifier" class="form-control" />
		</div>
		<div class="checkbox">
	        <label>
	            <input type="checkbox" name="private" > Private Group
	        </label>
	    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="addNewTableGroupName()">Create</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="modifierInputModel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select The Required Modifiers</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
			<table class="table modifier-table">
				<thead>
					<tr>
						<th>Sr.</th>
						<th>Modifier</th>
						<th>Modifier Value</th>
						<th>Required</th>
					</tr>
				</thead>
				<tbody>
					<tr *ngFor="let data_type_modifier of data_type_modifiers; let i = index;">
						<td>1</td>
						<td>fds</td>
						<td>
							<div class="form-group" *ngIf="data_type_modifier.name === 'comment' || data_type_modifier.name === 'default' || data_type_modifier.name === 'storedAs' || data_type_modifier.name === 'virtualAs'">
								<input type="text" name="input_for_modifier" class="form-control" 
								 />
							</div>

							<div class="form-group" *ngIf="data_type_modifier.name === 'after'">
								<select name="after_field" class="form-control" >
									<option  value="gfd">gdf
									</option>
								</select>
							</div>
						</td>
						<td>
							<input type="checkbox" name="required" onclick="addNewModifier(i)" />
						</td>
					</tr>
				</tbody>
			</table>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="sqlIndexInputModel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select The Required Indexes</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
			<table class="table indexes-table">
				<thead>
					<tr>
						<th>Sr.</th>
						<th>Index Name</th>
						<th>Required</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>3</td>
						<td>{fd</td>
						<td>
							<input type="checkbox" name="required" onclick="addNewIndex(i)" />
						</td>
					</tr>
				</tbody>
			</table>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="compositeIndexInputModel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add The Required Indexes With Composite Inputs</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
			<table class="table composite-indexes-table">
				<thead>
					<tr>
						<th><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFiSURBVGhD7Zk5TgNBEEUnAyJEynoqApBI2S4ACVsEV8DcBMQNSOAuQAa8HyB9jVrqmfbSLaue9JK2q6a+pbGtqS4IgqnYxVt8xbdK6tqaQbMUcY5f+NuImuUMR3GKqWYtqNkGsYPfmGrSgp+4jVnu0At/cIIHuL9gdc0n1Aw+0zVmeUEvesTa6IP0mTRjlnf0oiOsjWbwmTRjlg/0okOsjWbwmTRjllkFWcGLnjoroWqQdfQ+UmclRBAviiDofWQEQe8TQUqIIAkiiBf1g6zixgD30PtInaXe23cNnbkEuUR/fR7eoxNBvCiCTOFCgizNzT6U+B1JEEG8KIKg95ERBL1PBCkhgiSoGkT/AB566qyEqkFmSVGQpXmI3eJaQTsSn+kZs2jx6EVasqhRS4ueK8yi1VtLS9C+Wr1t4SBaXoYe4yhOsLX19OgQ/2h7eoP6Akgt8xehrq17YhODICii6/4AKeYLm3RM7hQAAAAASUVORK5CYII=" onclick="addNewCompositeIndex()"></th>
						<th>Index Name</th>
						<th>Field Name</th>
						<th>Selected Fields For Composite Index</th>
						<th>Remove</th>
					</tr>
				</thead>
				<tbody>
					<tr *ngFor="let c_index of selTable['composite_indexes']; let i = index;">
						<td>3</td>
						<td>
							<div class="form-group">
								<select name="index_name" class="form-control">
									<option value="hf">gfd</option>
								</select>
							</div>
						</td>
						<td>
							<div class="input-group">
								<select name="after_field" class="form-control">
									<option  value="gdfg">gdgdf</option>
								</select>
								<span class="input-group-addon"><button onclick="addCompositeIndexValue(i)">Add</button></span>
							</div>
						</td>
						<td>
							[gfdgd]
						</td>
						<td><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAEjSURBVGhD7Zk9agJRFIVnB1bBbWgR12FrbZAUgq21ja7AXkvLtJaWCWQBWYZ2gSTnwBMuj4fj6H0zYTwffM3lvJ8DU70phBDi4XmF+xKZ+ddM4Q/8LZEZZhvhBe4u+AavKXGWWa5J7XWWZ7qzhqkL5ZRnuqMid5ilyBCuapZnijJ6cJLZAczOHKa+Z0+XMDsqUkEVqULrixzhh/ETxhnObIZr4gxttMgBWjowznBm4Zo4Q1WkCioSZhYV8UBFwsyiIh6oSJhZVMQDFQkzi4p4oCJhZlERD1pf5AvaR7YZjDOc2QzXxBnaaBFPW1NkAbPDv0ipwz0dw+x04QmmLuAhn4ieYC2M4DdMXeQeuSf3rpVnuIXv0D663SL32MA+FEK4UxR/dgb0zufBmgUAAAAASUVORK5CYII=" onclick="removeCompositeIndex(i)"></td>
					</tr>
				</tbody>
			</table>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection