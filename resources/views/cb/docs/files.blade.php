@extends("cb.layouts.docs")

@section("docs")
<h3>Licenses</h3>
<hr>
<h4 id="licenses1">Create New License<a href="docs/#licenses1s"> ↻</a></h4>
<p>Select the licenses top nav. Press Create New License button that will prompt you for total no. of licenses and expiry date. Enter the detail to create new license. The total no. of licenses is 1 if the license is given to single user. for server type of licenses or shared licenses this can be more than one.</p>
<hr>
<h4 id="licenses2">Edit License<a href="docs/#licenses2s"> ↻</a></h4>
<p>Every license list item will have edit link. Click this link to increase the number of licenses and change the expiry date.</p>
<hr>
<h4 id="licenses3">License Detail<a href="docs/#licenses3s"> ↻</a></h4>
<p>Click the license detail link on every list item to goto new screen with details of licenses usage. This details screen will have manual activate or deactivate option for admin purpose. This screen will display how the number of licenses are being used for shared license system, hardwarecode, computer name and user.</p>
<hr>
<h4 id="licenses4">Activate License<a href="docs/#licenses4s"> ↻</a></h4>
<p>You can see this activate link in license details screen to activate the license manually for admin purpose.</p>
<hr>
<h4 id="licenses5">Deactivate License<a href="docs/#licenses5s"> ↻</a></h4>
<p>You can see this deactivate link in license details screen to deactivate the license manually for admin purpose.</p>
<hr>
<h4 id="licenses6">Test Bench<a href="docs/#licenses6s"> ↻</a></h4>
<p>Test Bench button on the top right corner of the listing screen will take you to new test bench screen. There simulation of how software sends json to backend for activation and deactivation is demonstrated. Also there is simulation of the how client websites which sells software are required to send to request to this backend to get license keys for purchased user is shown.</p>
@endsection