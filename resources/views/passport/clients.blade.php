@extends('layouts.app')

@section('content')
<div ng-app="myApp" ng-controller="passportCtrl">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        OAuth Clients
                    </span>
                    <a class="modal-trigger" href="#modal1">Create New Client</a>
                </div>
            </div>

            <div class="panel-body">
                <!-- Current Clients -->
                <p class="m-b-none" ng-if="clients.length === 0">
                    You have not created any OAuth clients.
                </p>

                <table class="table table-borderless m-b-none" ng-if="clients.length > 0">
                    <thead>
                        <tr>
                            <th>Client ID</th>
                            <th>Name</th>
                            <th>Secret</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="client in clients">
                            <!-- ID -->
                            <td style="vertical-align: middle;">
                                @{{ client.id }}
                            </td>

                            <!-- Name -->
                            <td style="vertical-align: middle;">
                                @{{ client.name }}
                            </td>

                            <!-- Secret -->
                            <td style="vertical-align: middle;">
                                <code>@{{ client.secret }}</code>
                            </td>

                            <!-- Edit Button -->
                            <td style="vertical-align: middle;">
                                <a class="action-link" ng-click="openDialogForEdit(client)">
                                    Edit
                                </a>
                            </td>

                            <!-- Delete Button -->destroy
                            <td style="vertical-align: middle;">
                                <a class="action-link text-danger" ng-click="destroy(client)">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Client Modal -->
		<div id="modal1" class="modal modal-fixed-footer">
    		<div class="modal-content">
    			<h4 class="modal-title">Create Client</h4>
    		  	<!-- Form Errors -->
                <div class="alert alert-danger" ng-if="createForm.errors.length > 0">
                    <p><strong>Whoops!</strong> Something went wrong!</p>
                    <br>
                    <ul>
                        <li ng-repeat="error in createForm.errors">
                            @{{ error }}
                        </li>
                    </ul>
                </div>

                <!-- Create Client Form -->
                <form class="form-horizontal" role="form">
                    <!-- Name -->
                    <div class="form-group">
                        <label class="col-md-3 control-label">Name</label>

                        <div class="col-md-7">
                            <input id="create-client-name" type="text" class="form-control" ng-model="newClient.name">

                            <span class="help-block">
                                Something your users will recognize and trust.
                            </span>
                        </div>
                    </div>

                    <!-- Redirect URL -->
                    <div class="form-group">
                        <label class="col-md-3 control-label">Redirect URL</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" name="redirect" ng-model="newClient.redirect">

                            <span class="help-block">
                                Your application's authorization callback URL.
                            </span>
                        </div>
                    </div>
                </form>
    		</div>
    		<div class="modal-footer">
                <button type="button" class="modal-action modal-close waves-effect waves-green btn-flat" data-dismiss="modal">Close</button>
                <button type="button" class="modal-action modal-close waves-effect waves-green btn-flat" ng-click="addnew()">
                    Create
                </button>
    		</div>
		</div>

        <!-- Edit Client Modal -->
        <div id="modal2" class="modal modal-fixed-footer">
            <div class="modal-content">
                <h4 class="modal-title">Edit Client</h4>
                <!-- Form Errors -->
                <div class="alert alert-danger" ng-if="editForm.errors.length > 0">
                    <p><strong>Whoops!</strong> Something went wrong!</p>
                    <br>
                    <ul>
                        <li ng-repeat="error in editForm.errors">
                            @{{ error }}
                        </li>
                    </ul>
                </div>

                <!-- Edit Client Form -->
                <form class="form-horizontal" role="form">
                    <!-- Name -->
                    <div class="form-group">
                        <label class="col-md-3 control-label">Name</label>

                        <div class="col-md-7">
                            <input id="edit-client-name" type="text" class="form-control"
                                                         ng-model="editForm.name">

                            <span class="help-block">
                                Something your users will recognize and trust.
                            </span>
                        </div>
                    </div>

                    <!-- Redirect URL -->
                    <div class="form-group">
                        <label class="col-md-3 control-label">Redirect URL</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" name="redirect"
                                             ng-model="editForm.redirect">

                            <span class="help-block">
                                Your application's authorization callback URL.
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-action modal-close waves-effect waves-green btn-flat" data-dismiss="modal">Close</button>
                <button type="button" class="modal-action modal-close waves-effect waves-green btn-flat" ng-click="edit()">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
@endsection