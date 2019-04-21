<?php

namespace App\Traits;

use App\VirtualDomain;
use App\VirtualUser;
use App\VirtualAlias;
use App\Mail\AliasVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

trait EmailAccounts
{
	public function emailListView()
	{
		\Log::Info(request()->ip()." visited email list for app id ".$this->app_id);
		$emails = VirtualUser::where(['user_id' => \Auth::user()->id])->orderBy('domain_id', 'asc')->paginate(10);
		return view($this->theme.'.email.email_accounts')->with(['emails' => $emails, 'page' => $request->page??1]);
	}

	public function createEmailAccountView(Request $request)
	{
		$domains = VirtualDomain::select(['id','name'])->where([
			'user_id' => \Auth::user()->id,
			'verified' => "done",
		])->get();
		$alias = VirtualAlias::where([
			'user_id' => \Auth::user()->id,
			'verified' => "done",
		])->pluck('email');
		return view($this->theme.'.email.create_email_account')->with(['domains' => $domains, 'alias' => $alias]);
	}

	public function addNewUser(Request $request)
	{
		$request->validate([
			'domain_id' => 'required',
			'user' => 'required|string|max:255',
			'email' => 'required|string|max:255|unique:virtual_users',
			'alias' => 'max:255',
			'password' => 'required|string|min:6|confirmed',
		]);
		$domain = VirtualDomain::findOrFail($request->domain_id);
		if(!empty($domain)){
			if($domain->user_id != \Auth::user()->id || $domain->verified != 'done'){
				\Log::Info('domain invalid');
				return redirect()->route('c.email.list.view');
			}
		}
		if(!empty($request->alias)){
			$ralias = explode(' ', $request->alias);
			$alias = VirtualAlias::whereIn('email', $ralias)->get();
			if(count($alias) != count($ralias)){
				\Log::Info('alias count invalid');
				return redirect()->route('c.email.list.view');
			}
			foreach ($alias as $a) {
				if($a->user_id != \Auth::user()->id || $a->verified != 'done'){
					\Log::Info('alias auth invalid');
					return redirect()->route('c.email.list.view');
				}
			}
		}
		$ssha = $this->ssha($request->password);
		VirtualUser::create([
			'user_id' => \Auth::user()->id,
			'domain_id' => $request->domain_id,
			'email' => $request->email,
			'password' => $ssha,
			'mailbox' => $domain->name.'/'.$request->user.'/Maildir/',
			'alias' => $request->alias??null,
		]);
		\Log::Info(request()->ip()." added email user ".$request->email." for app id ".$this->app_id);
		return redirect()->route('c.email.list.view');
		return ['status' => 'success'];
	}

	public function deleteEmailAccount(Request $request)
	{
		\Log::Info(request()->ip()." deleted email user for domain ".$request->domain_id." app id ".$this->app_id);
		VirtualUser::destroy($request->id);
		return ['status' => 'success'];
	}

	public function domainListView()
	{
		\Log::Info(request()->ip()." visited domain list for app id ".$this->app_id);
		$domains = VirtualDomain::where(['user_id' => \Auth::user()->id])->paginate(10);
		return view($this->theme.'.email.domains_list')->with(['domains' => $domains, 'page' => $request->page??1]);
	}

	public function addNewDomain(Request $request)
	{
		\Log::Info(request()->ip()." added new domain ".$request->name." for app id ".$this->app_id);
		$request->validate([
			'name' => ['required', 'string', 'max:255', 'unique:virtual_domains', function($attribute, $value, $fail)use($request){
				if($this->is_valid_domain_name($request->domain) == 'N'){
					$fail("invalid domain name");
				}
			}],
		]);
		VirtualDomain::create([
			'user_id' => \Auth::user()->id,
			'name' => $request->name,
			'verified' => bcrypt($request->name),
			'expiry_date' => $this->expiry_date($request->name),
		]);
		return redirect()->route('c.domain.list.view');
	}

	public function deleteDomain(Request $request)
	{
		$vu = VirtualUser::where(['user_id'=>\Auth::user()->id, 'domain_id'=>$request->id]);
		foreach ($vu as $u) {
			VirtualUser::destroy($u->id);
		}
		VirtualDomain::destroy($request->id);
		return ['status'=> 'success'];
	}

	public function verifyNewDomainView($id)
	{
		$domain = VirtualDomain::findOrFail($id);
		\Log::Info(request()->ip()." visited verify domain page for domain ".$domain." for app id ".$this->app_id);
		return view($this->theme.'.email.add_domain')->with(['domain' => $domain]);
	}

	public function aliasListView()
	{
		\Log::Info(request()->ip()." visited alias list for app id ".$this->app_id);
		$aliases = VirtualAlias::where(['user_id' => \Auth::user()->id])->paginate(10);
		return view($this->theme.'.email.alias_list')->with(['aliases' => $aliases, 'page' => $request->page??1]);
	}

	public function addNewAlias(Request $request)
	{
		$request->validate(['email' => 'required|email|unique:virtual_alias']);
		$d = explode('@', $request->email);
		$code = mt_rand(100000, 999999);
		VirtualAlias::create([
			'user_id' => \Auth::user()->id,
			'email' => $request->email,
			'domain' => $d[1],
			'verified' => $code,
		]);
		Mail::to($request->email)->send(new AliasVerification($code));
		return redirect()->route('c.alias.list.view');
	}

	public function deleteAlias(Request $request)
	{
		VirtualAlias::destroy($request->id);
		return ['status'=> 'success'];
	}

	public function verifyAlias(Request $request)
	{
		$alias = VirtualAlias::findOrFail($request->id);
		if($alias->verified == $request->code){
			$alias->update(['verified' => 'done']);
			$alias->save();
			return ['status' => 'success'];
		}
		return ['status' => 'failed'];
	}

	public function templateListView(Request $request)
	{
		$aliases = VirtualAlias::where(['user_id' => \Auth::user()->id])->paginate(10);
		return view($this->theme.'.email.alias_list')->with(['aliases' => $aliases, 'page' => $request->page??1]);
	}

	public function getTxtRecord(Request $request)
	{
		\Log::Info(request()->ip()." attempted to verify TXT record for domain".$request->id." for app id ".$this->app_id);
		$record = VirtualDomain::findOrFail($request->id);
		$result = dns_get_record($record->name, DNS_TXT);
		if(count($result) != 0){
			if($record->verified == $result[0]['txt']){
				\Log::Info(request()->ip()." Verified TXT record for domain ".$request->id." for app id ".$this->app_id);
				$record->update(['verified' => 'done', 'expiry_date' => $this->expiry_date($record->name)]);
				return ['status' => 'success'];
			}
			return ['status' => $result[0]['txt']];
		}
		return ['status' => 'no txt records found for '.$record->name];
	}

	public function getPageContents(Request $request)
	{
		\Log::Info(request()->ip()." attempted to verify page record for domain id".$request->id." for app id ".$this->app_id);
		$record = VirtualDomain::findOrFail($request->id);
		$url ='http://'.$record->name.'/honeyweb-domain-verification';
		$data = $this->httpGet($url);
		if($record->verified == $data){
			\Log::Info(request()->ip()." Verified page record for domain ".$domain." for app id ".$this->app_id);
			$record->update(['verified' => 'done', 'expiry_date' => $this->expiry_date($record->name)]);
			return ['status' => 'success'];
		}
		return ['status' => $data];
	}

	public function httpGet($url, $data = array())
	{
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'GET',
		        'content' => http_build_query($data)
		    ),
		    "ssl"=>array(
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
		    ),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ }
		return $result;
	}

	private function curl_get_contents($url)
	{
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	    $output = curl_exec($ch);
	    curl_close($ch); 
	    return $output;
	}

	private function expiry_date($domain){
		$current = \Carbon\Carbon::now();
		return $current->addDays(365);
	}

	private function ssha($passwordplain)
	{
		$salt = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',4)),0,4); 
		$encrypted_password = '{SSHA}' . base64_encode(sha1( $passwordplain.$salt, TRUE ). $salt); 
		return $encrypted_password;
		// base64_encode(pack('H*',sha1($passwordplain))); 
	}

	private function is_valid_domain_name($domain_name)
	{
	    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
	            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
	            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
	}

}