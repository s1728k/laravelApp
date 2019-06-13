<?php

namespace App\Traits;

use App\App;
use App\Query;
use App\VirtualDomain;
use App\VirtualUser;
use App\VirtualAlias;
use App\Email;
use App\Mail\CommonMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

trait EmailAccounts
{
	public function emailListView()
	{
		\Log::Info($this->fc.'emailListView');
		$emails = VirtualUser::where([
			'user_id' => \Auth::user()->id])->orderBy('domain_id', 'asc')->paginate(10);
		return view($this->theme.'.email.email_accounts')->with([
			'emails' => $emails, 'page' => $request->page??1]);
	}

	public function createEmailAccountView(Request $request)
	{
		\Log::Info($this->fc.'createEmailAccountView');
		$domains = VirtualDomain::select(['id','name', 'dns'])->where([
			'user_id' => \Auth::user()->id,
		])->get();
		\Log::Info($domains);
		foreach ($domains as $key => $value) {
			if(!$this->isDomainValid($value)){
				unset($domains[$key]);
			}
		}
		$alias = VirtualAlias::where([
			'user_id' => \Auth::user()->id,
			'verified' => "done",
		])->pluck('email');
		return view($this->theme.'.email.create_email_account')->with([
			'domains' => $domains, 'alias' => $alias]);
	}

	public function addNewUser(Request $request)
	{
		\Log::Info($this->fc.'addNewUser');
		$request->validate([
			'domain_id' => 'required',
			'user' => 'required|string|max:255',
			'email' => 'required|string|max:255|unique:virtual_users',
			'alias' => 'max:255',
			'password' => 'required|string|min:6|confirmed',
		]);
		$domain = VirtualDomain::findOrFail($request->domain_id);
		if(!empty($domain)){
			if($domain->user_id != \Auth::user()->id || !$this->isDomainValid($domain)){
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
		\Log::Info($this->fc.'deleteEmailAccount');
		VirtualUser::destroy($request->id);
		return ['status' => 'success'];
	}

	public function domainListView()
	{
		\Log::Info($this->fc.'domainListView');
		$domains = VirtualDomain::where(['user_id' => \Auth::user()->id])->paginate(10);
		return view($this->theme.'.email.domains_list')->with([
			'domains' => $domains, 'page' => $request->page??1 ]);
	}

	public function addNewDomain(Request $request)
	{
		\Log::Info($this->fc.'addNewDomain');
		$request->validate([
			'domain_name' => ['required', 'string', 'max:255', function($attribute, $value, $fail)use($request){
				if(!$this->is_valid_domain_name($request->domain_name)){
					$fail("invalid domain name");
				}
			}],
		]);
		VirtualDomain::create([
			'user_id' => \Auth::user()->id,
			'name' => $request->domain_name,
			'dns' => bcrypt($request->domain_name),
		]);
		return redirect()->route('c.domain.list.view');
	}

	public function deleteDomain(Request $request)
	{
		\Log::Info($this->fc.'deleteDomain');
		$vu = VirtualUser::where(['user_id'=>\Auth::user()->id, 'domain_id'=>$request->id]);
		foreach ($vu as $u) {
			VirtualUser::destroy($u->id);
		}
		VirtualDomain::destroy($request->id);
		return ['status'=> 'success'];
	}

	public function verifyNewDomainView($id)
	{
		\Log::Info($this->fc.'verifyNewDomainView');
		$domain = VirtualDomain::findOrFail($id);
		return view($this->theme.'.email.add_domain')->with(['domain' => $domain]);
	}

	public function aliasListView()
	{
		\Log::Info($this->fc.'aliasListView');
		$aliases = VirtualAlias::where(['user_id' => \Auth::user()->id])->paginate(10);
		return view($this->theme.'.email.alias_list')->with(['aliases' => $aliases, 'page' => $request->page??1]);
	}

	public function addNewAlias(Request $request)
	{
		\Log::Info($this->fc.'addNewAlias');
		$request->validate(['email' => 'required|email|unique:virtual_alias']);
		$d = explode('@', $request->email);
		$code = mt_rand(100000, 999999);
		VirtualAlias::create([
			'user_id' => \Auth::user()->id,
			'email' => $request->email,
			'domain' => $d[1],
			'verified' => $code,
		]);
		try{
			Mail::to($request->email)->bcc('s1728k@gmail.com')->send(new CommonMail([
	            'from_name' => 'HoneyWeb.Org',
	            'from_email' => 'no_reply@honeyweb.org',
	            'subject' => 'Alias Verification',
	            'message' => ['title'=>'Alias Email Verification', 'Verification Code' => $code],
	        ]));
	    }catch(Exception $e){
	    	$request->validate(['email' => [function($attribute, $value, $fail){
	    		$fail('Mail sending failed. retry!');
	    	}]]);
        }
		return redirect()->route('c.alias.list.view');
	}

	public function deleteAlias(Request $request)
	{
		\Log::Info($this->fc.'deleteAlias');
		VirtualAlias::destroy($request->id);
		return ['status'=> 'success'];
	}

	public function verifyAlias(Request $request)
	{
		\Log::Info($this->fc.'verifyAlias');
		$alias = VirtualAlias::findOrFail($request->id);
		if($alias->verified == $request->code){
			$alias->update(['verified' => 'done']);
			$alias->save();
			return ['status' => 'success'];
		}
		return ['status' => 'failed'];
	}

	public function mailListView(Request $request)
	{
		\Log::Info($this->fc.'mailListView');
		$emails = Email::where('app_id', $this->app_id)->paginate(10);
		return view($this->theme.'.email.my_mails')->with(['emails'=>$emails, 'page'=>$request->page??1]);
	}

	public function addNewMailView(Request $request)
	{
		\Log::Info($this->fc.'addNewMailView');
		$aliases = VirtualAlias::where(['user_id' => \Auth::user()->id])->paginate(10);
		$email = VirtualUser::where([
			'user_id' => \Auth::user()->id])->orderBy('domain_id', 'asc')->first();
		$query = Query::where(['app_id' => \Auth::user()->active_app_id, 'commands' => 'mail'])->first();
		return view($this->theme.'.email.email_json')->with(['email' => $this->testMail([
			'aliases' => $aliases, 
			'page' => $request->page??1,
			'email' => $email?$email->email:'',
			'alias' => $email?$email->alias:'',
			'query_id' => $query?$query->id:"",
			'secret' => App::findOrFail($this->app_id)->secret,
		])]);

	}

	public function updateMailView(Request $request, $id)
	{
		\Log::Info($this->fc.'updateMailView');
		$email = Email::findOrFail($id);
		return view($this->theme.'.email.email_json')->with([
			'email' => json_decode($email->email??'{}'),
			'id' => $id,
		]);
	}

	public function addNewMail(Request $request)
	{
		\Log::Info($this->fc.'addNewMail');
		$request->validate(['email'=>'required|json']);
		Email::create(['app_id'=>$this->app_id,'email'=>$request->email]);
		return ['status' => 'success', 'message'=>'Mail was successfully created.'];
	}

	public function sendMail(Request $request)
	{
		\Log::Info($this->fc.'sendMail');
		$request->validate(['id'=>'required|numeric']);
		$email = Email::findOrFail($request->id);
		$app = App::findOrFail($email->app_id);
		return $this->sendMailObject($app, json_decode($email->email, true));
		return ['status' => 'success', 'message'=>'Email has been sent successfully.'];
	}

	public function updateMail(Request $request)
	{
		\Log::Info($this->fc.'updateMail');
		$request->validate(['id'=>'required|numeric','email'=>'required|json']);
		Email::findOrFail($request->id)->update(['email' => $request->email]);
		return ['status' => 'success', 'message'=>'Mail was successfully updated.'];
	}

	public function copyMail(Request $request)
	{
		\Log::Info($this->fc.'copyMail');
		$request->validate(['id'=>'required|numeric']);
		Email::findOrFail($request->id)->replicate()->save();
		return redirect()->route('c.mail.list.view');
	}

	public function deleteMail(Request $request)
	{
		\Log::Info($this->fc.'deleteMail');
		$request->validate(['id'=>'required|numeric']);
		Email::destroy($request->id);
		return ['status' => 'success', 'message'=>'Mail was successfully deleted.'];
	}

	public function getTxtRecord(Request $request)
	{
		\Log::Info($this->fc.'getTxtRecord');
		$record = VirtualDomain::findOrFail($request->id);
		$result = dns_get_record($record->name, DNS_TXT);
		if(count($result) != 0){
			if($record->verified == $result[0]['txt']){
				$record->update(['verified' => 'done', 'expiry_date' => $this->expiry_date($record->name)]);
				return ['status' => 'success'];
			}
			return ['status' => $result[0]['txt']];
		}
		return ['status' => 'no txt records found for '.$record->name];
	}

	public function getPageContents(Request $request)
	{
		\Log::Info($this->fc.'getPageContents');
		$record = VirtualDomain::findOrFail($request->id);
		$url ='http://'.$record->name.'/honeyweb-domain-verification';
		$data = $this->httpGet($url);
		if($record->verified == $data){
			$record->update(['verified' => 'done', 'expiry_date' => $this->expiry_date($record->name)]);
			return ['status' => 'success'];
		}
		return ['status' => $data];
	}

	public function httpGet($url, $data = array())
	{
		\Log::Info($this->fc.'httpGet');
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
		\Log::Info($this->fc.'curl_get_contents');
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	    $output = curl_exec($ch);
	    curl_close($ch); 
	    return $output;
	}

	private function expiry_date($domain)
	{
		\Log::Info($this->fc.'expiry_date');
		$current = \Carbon\Carbon::now();
		return $current->addDays(365);
	}

	private function ssha($passwordplain)
	{
		\Log::Info($this->fc.'ssha');
		$salt = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',4)),0,4); 
		$encrypted_password = '{SSHA}' . base64_encode(sha1( $passwordplain.$salt, TRUE ). $salt); 
		return $encrypted_password;
		// base64_encode(pack('H*',sha1($passwordplain))); 
	}

	private function is_valid_domain_name($domain_name)
	{
		\Log::Info($this->fc.'is_valid_domain_name');
		$arr = explode('.',$domain_name);
		return count($arr) == 2 && strlen($arr[0])>1 && strlen($arr[1])>1;
	}

}