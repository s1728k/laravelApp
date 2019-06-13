@extends("cb.layouts.docs")

@section("docs")
<div class="jumbotron">
<h1 >Apps</h1>
<hr>
<h2 id="apps1">Create New App<a href="/docs/#apps1s"> ↻</a></h2>
<p>Select the MyApps top nav if not already selected. Press Create New App button that will prompt you for app name. Enter the reasonable name of the app and press ok. The backend creates app with unique app id and app secret. Backend also creates users authenticatable table when you create an app. Alteast one authenticatable table is required to access data from this backend. You can have multiple authenticatable tables for a single app.</p>
<hr>
<h2 id="apps2">Active App Id<a href="/docs/#apps2s"> ↻</a></h2>
<p>This is the ID of active app you last modified. This is stored in the backend. Whenever you login the app with this active app id is opened for modification. Before you make any modification make sure you are modifying for the correct app. This id is displayed on the top left corner of every page.</p>
<hr>
<h2 id="apps3">Activate App<a href="/docs/#apps3s"> ↻</a></h2>
<p>In the app list every list item will have a link for activating the app. Click this link to activate the app when you wish do any modification to that perticular app. This last activated app remain active until you activate different app.</p>
<hr>
<h2 id="apps4">Update App<a href="/docs/#apps4s"> ↻</a></h2>
<p>This link will be present on every app list item. Click this link to edit app name and refresh app secret.</p>
<hr>
<h2 id="apps5">Permissions<a href="/docs/#apps5s"> ↻</a></h2>
<p>Click permissions link on app list item to set the permissions for accessing the tables data from the backend. This link will direct to set permissions screen where each table in the app is to be assigned permissions for every authenticatable by checking the checkbox against each combination. This has to be done for Create, Read, Update and Delete separately. You can check for guest to assign permission for every authenticatable.</p>
</div>
@endsection