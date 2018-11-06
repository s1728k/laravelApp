@extends('layouts.app')

@section('content')
	<div>
        <div ng-if="tokens.length > 0">
            <div class="panel panel-default">
                <div class="panel-heading">Authorized Applications</div>

                <div class="panel-body">
                    <!-- Authorized Tokens -->
                    <table class="table table-borderless m-b-none">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Scopes</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr ng-repeat="token in tokens">
                                <!-- Client Name -->
                                <td style="vertical-align: middle;">
                                    @{{ token.client.name }}
                                </td>

                                <!-- Scopes -->
                                <td style="vertical-align: middle;">
                                    <span ng-if="token.scopes.length > 0">
                                        @{{ token.scopes.join(', ') }}
                                    </span>
                                </td>

                                <!-- Revoke Button -->
                                <td style="vertical-align: middle;">
                                    <a class="action-link text-danger" ng-click="revoke(token)">
                                        Revoke
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection