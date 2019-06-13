@extends("cb.layouts.docs")

@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/routemap">Route Map</a></h4>
@endsection
@section("docs")
<div class="jumbotron">
	<h1>Route Map</h1> 
	<p>Site requires users to register. Unregistered user can see only landing page and docs page. Registered user will get access to control panel after recharging the account.</p>
	<h2>Landing Page</h2> 
	<p>Landing page gives brief presentation on purpose of this website.</p>
	<h2>Menu Page</h2> 
	<p><i>Webpage</i> that you get from clicking menu items like <i>My Apps, Tables</i> etc., </p><p><i>Menu page</i> shall have <i>title, button group</i> and <i>datatable sections</i>. </p><p><i>Button group</i> shall have one or more buttons that are related to modification of the whole dataset or navigation to another datatable of same level of menu item. </p><p><i>Datatable</i> shall have <i>action</i> column which will have many <i>action hyperlinks</i> for modification of data record.</p>
	<h2>Logged-In User Name Dropdown Menu</h2> 
	<p>This dropdown menu shall have items related to user and user account.</p>
	<h2>Recharge Offers Page</h2> 
	<p>If you dont have balance in you account, you will be redirected to this page to recharge your account balance.</p>
	@guest
	@else
	
	@endguest
</div>
@endsection