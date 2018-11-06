<?php

namespace App\Traits;

use App\App;
use App\License;
use App\LicenseDetail;
use Illuminate\Http\Request;

trait LicensesSoftwares
{
	public function createNewLicense(Request $request)
    {
        \Log::Info(request()->ip()." created license for app id ".$this->app_id);
        $id = License::create([
            'license_key' => bcrypt(uniqid(rand(), true)),
            'total_licenses' => $request->total_licenses,
            'activated_licenses' => 0,
            'created_by' => $this->app_id,
            'expiry_date' => $request->expiry_date,
            'price_id' => 0,
        ])->id;
        for ($i = 0; $i<$request->input("total_licenses"); $i++)
        {
            LicenseDetail::create([
                'license_id' => $id,
                'hardware_code' => "Empty", 
                'computer_name' => "Empty", 
                'computer_user' => "Empty",
            ]);
        }
        return redirect()->route('l.license.list.view');
    }

    public function getLicenseKey(Request $request)
    {
        \Log::Info(request()->ip()." requested for license key for app id ".$this->app_id);
        if($request->app_secret == App::findOrFail($request->app_id)->secret){
            $license = License::create([
                'license_key' => bcrypt(uniqid(rand(), true)),
                'total_licenses' => $request->total_licenses,
                'activated_licenses' => 0,
                'created_by' => $request->app_id,
                'expiry_date' => $request->expiry_date,
                'price_id' => 0,
            ]);
            for ($i = 0; $i<$request->input("total_licenses"); $i++)
            {
                LicenseDetail::create([
                    'license_id' => $license->id,
                    'hardware_code' => "Empty", 
                    'computer_name' => "Empty", 
                    'computer_user' => "Empty",
                ]);
            }
        }
        return ["serial_no" => $license->id, "license_key" => $license->license_key];
    }

    public function licenseListView(Request $request)
    {
        \Log::Info(request()->ip()." visited license list page for app id ".$this->app_id);
        $licenses = License::where('created_by', $this->app_id)->paginate(10);
        return view($this->theme.'.license.license_list')->with([
            'licenses' => $licenses,
            'page' => $request->query('page')??1,
            'edit' => json_encode(array_fill(0, count($licenses), array('display' => 'none'))),
        ]);
    }

    public function licenseDetailsView(Request $request, $id)
    {
        \Log::Info(request()->ip()." visited license details page for app id ".$this->app_id);
        $licenseDetails = LicenseDetail::where('license_id', $id)->paginate(10);
        return view($this->theme.'.license.license_detail')->with([
            'licenseDetails' => $licenseDetails, 
            'license_id' => $id, 
            'page' => $request->query('page')??1,
            'license_key' => License::findOrFail($id)->license_key,
        ]);
    }

    public function testBenchView()
    {
        \Log::Info(request()->ip()." visited license test bench page for app id ".$this->app_id);
        return view($this->theme.'.license.test_bench');
    }

    public function updateLicense(Request $request, $id)
    {
        \Log::Info(request()->ip()." updated license for app id ".$this->app_id);
        $license = License::findOrFail($id);
        if($request->total_licenses < $license->total_licenses ){
            return redirect()->route('l.license.list.view');
        }
        if($license->total_licenses < $request->total_licenses){
            for ($i = 0; $i<$request->total_licenses - $license->total_licenses; $i++)
            {
                LicenseDetail::create([
                    'license_id' => $license->id,
                    'hardware_code' => "Empty", 
                    'computer_name' => "Empty", 
                    'computer_user' => "Empty",
                ]);
            }
        }
        $license->update([
            'total_licenses' => $request->total_licenses,
            'expiry_date' => $request->expiry_date,
        ]);
        return redirect()->route('l.license.list.view');
    }

    public function deleteLicense(Request $request, $id)
    {
        \Log::Info(request()->ip()." deleted license for app id ".$this->app_id);
        // if(!empty($request->_token)){
        //     $licenseDetails = LicenseDetail::where('license_id', $id)->get();
        //     foreach ($licenseDetails as $licenseDetail) {
        //         LicenseDetail::destroy($licenseDetail->id);
        //     }
        //     License::destroy($id);
        // }
        return redirect()->route('l.license.list.view');
    }

    public function activateLicense(Request $request)
    {
        \Log::Info(request()->ip()." activated license for app id ".$this->app_id);
        $status = "License key with this serial number did not match";
        $license_no = 0;
        $license = License::findOrFail($request->serial_no);
        if ($license->expiry_date < date('Y-m-d')){
            return ["status" => "License Expired"];
        }
        $status = ucwords($request->hardware_code) == "Empty" ? "Hardware code cannot be empty" : $status;
        if($license->license_key == $request->license_key && ucwords($request->hardware_code) !== "Empty"){ 
            if(!empty($request->license_no)){
                $licenseDetails = json_decode(LicenseDetail::where(['license_id' => $license->id])->get(), true)??[];
                if(count($licenseDetails) > $request->license_no - 1){
                    $emptyLicense = $licenseDetails[$request->license_no-1];
                    if($emptyLicense['hardware_code'] == 'Empty'){
                        LicenseDetail::findOrFail($emptyLicense['id'])->update([
                            "hardware_code" => $request->hardware_code,
                            "computer_name" => $request->computer_name??"Empty",
                            "computer_user" => $request->computer_user??"Empty",
                        ]);
                        $license->update([
                            'activated_licenses' => $license->activated_licenses + 1,
                        ]);
                        $status = "Activated";
                    }else{
                        return ["status" => "error"];
                    }
                }else{
                    $status = "No license available for this key";
                }
            }else{
                $emptyLicense = LicenseDetail::where(['license_id' => $license->id, 'hardware_code' => 'Empty'])->first();
                if(!empty($emptyLicense)){
                    foreach (json_decode(LicenseDetail::where(['license_id' => $license->id])->get(), true) as $key => $value) {
                        if($emptyLicense->id == $value['id']){
                            $license_no = $key + 1;
                        }
                    }
                    $emptyLicense->update([
                        "hardware_code" => $request->hardware_code,
                        "computer_name" => $request->computer_name??"Empty",
                        "computer_user" => $request->computer_user??"Empty",
                    ]);
                    $license->update([
                        'activated_licenses' => $license->activated_licenses + 1,
                    ]);
                    $status = "Activated";
                }else{
                    $status = "No license available for this key";
                }
            }
        }
        return [
            "status" => $status, 
            "expiry_date" => $license->expiry_date, 
            "license_no" => $license_no,
            "available_licenses" => $license->total_licenses - $license->activated_licenses,
        ];
    }

    public function deactivateLicense(Request $request)
    {
        \Log::Info(request()->ip()." de-activated license for app id ".$this->app_id);
        $status = "License key with this serial number did not match";
        $license = License::findOrFail($request->serial_no);
        $status = ucwords($request->hardware_code) == "Empty" ? "Hardware code cannot be empty" : $status;
        $status = $request->license_no == 0?"License Number Cannot Be Zero":$status;
        if($license->license_key == $request->license_key && ucwords($request->hardware_code) !== "Empty" && $request->license_no >0 ){
            $licenseDetails = json_decode(LicenseDetail::where(['license_id' => $license->id])->get(), true)??[];
            if(count($licenseDetails) >  $request->license_no - 1){
                $licenseDetail = $licenseDetails[$request->license_no - 1];

                if($licenseDetail['hardware_code'] == $request->hardware_code){
                    LicenseDetail::findOrFail($licenseDetail['id'])->update([
                        "hardware_code" => "Empty",
                    ]);
                    $license->update([
                        'activated_licenses' => $license->activated_licenses - 1,
                    ]);
                    $status = "De-Activated";
                }else{
                    $status = "Hardware code did not match";
                }
            }else{
                $status = "License number was incorrect.";
            }
        }
        return [
            "status" => $status,
            "available_licenses" => $license->total_licenses - $license->activated_licenses,
        ];
    }
	
}