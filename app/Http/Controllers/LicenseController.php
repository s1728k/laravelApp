<?php

namespace App\Http\Controllers;

use App\License;
use App\LicenseDetail;
use Illuminate\Http\Request;

class LicenseController extends Controller
{

    public function __construct()
    {

    }

    public function index()
    {
    	
    }

    public function createNewLicense(Request $request)
    {
        if(!empty($request->_token)){
            $id = License::create([
                'license_key' => bcrypt(uniqid(\Auth::user()->email, true)),
                'total_licenses' => $request->total_licenses,
                'activated_licenses' => 0,
                'created_by' => \Auth::user()->id,
                'expiry_date' => $request->expiry_date,
                'price_id' => 0,
            ])->id;
            for ($i = 0; $i<$this->request->input("total_licenses"); $i++)
            {
                LicenseDetail::create([
                    'license_id' => $id,
                    'hardware_code' => "Empty", 
                    'computer_name' => "Empty", 
                    'computer_user' => "Empty",
                ]);
            }
        }
        return $this->licenseListView();
    }

    public function licenseListView()
    {
        $licenses = License::where('created_by', \Auth::user()->id)->get();
        return view('l.license_list')->with(['licenses' => $licenses]);
    }

    public function licenseDetailsView($id)
    {
        $licenseDetails = LicenseDetail::where('license_id', $id)->get();
        return view('l.license_list')->with(['licenseDetails' => $licenseDetails]);
    }

    public function testBenchView($id)
    {
        $license = License::findOrFail($id);
        return view('l.test_bench')->with(['license' => $license]);
    }

    public function updateLicense($id)
    {
        if(!empty($request->_token)){
            License::findOrFail($id)->update([
                'total_licenses' => $request->total_licenses,
                'expiry_date' => $request->expiry_date,
            ]);
        }
        return $this->licenseListView();
    }

    public function deleteLicense($id)
    {
        $licenseDetails = LicenseDetail::where('license_id', $id)->get();
        foreach ($licenseDetails as $licenseDetail) {
            LicenseDetail::destroy($licenseDetail->id);
        }
        License::destroy($id);
        return $this->licenseListView();
    }

    
    
}
