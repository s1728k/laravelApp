<?php

namespace App\Http\Controllers;

use App\App;
use App\Traits\ScrapesWeb;
use App\Traits\Scraps\BarkDotCom;
use App\Traits\Scraps\YellDotCom;
use App\Traits\Scraps\bar_code_lookup_dot_com;
use Illuminate\Http\Request;

class ScrapingController extends CloudController
{
    use ScrapesWeb;
    use BarkDotCom;
    use YellDotCom;
    use bar_code_lookup_dot_com;

    public function __construct(Request $request)
    {
        
    }

    public function whoIsData()
    {
        // $milliseconds = round(microtime(true) * 1000);
        $arr = [
            "Registrar URL:",
            "Updated Date:",
            "Creation Date:",
            "Registrar Registration Expiration Date:",
            "Domain Status:",
            "Registrant Name:",
            "Registrant Organization:",
            "Registrant Street:",
            "Registrant City:",
            "Registrant State/Province:",
            "Registrant Postal Code:",
            "Registrant Country:",
            "Registrant Phone:",
            "Registrant Phone Ext:",
            "Registrant Fax:",
            "Registrant Email:",
        ];

        $url = "https://www.netim.com/ajax/domaine.php";
        $table = 'App\\Models\\App12_whoi';
        $domains = $table::all();
        foreach ($domains as $domain) {
            if(empty($domain->email)){
                $postData = ["whois" => 1, "DOMAINE" => $domain->name.'.'.$domain->zoneid];
                $resp = $this->httpPost($url, $postData);
                $page = $resp;

                $data["domain_name"] = $postData['DOMAINE'];
                $t = $this->getScrapeValue($page, "Registrant Name:", "<");
                $data["registrant"] = $t['v'];
                $page = $t['nPage'];

                $t = $this->getScrapeValue($page, "Registrant State/Province:", "<");
                $data["state"] = $t['v'];
                $page = $t['nPage'];

                $t = $this->getScrapeValue($page, "Registrant Country:", "<");
                $data["country"] = $t['v'];
                $page = $t['nPage'];

                $t = $this->getScrapeValue($page, "Registrant Phone:", "<");
                $data["mobile"] = $t['v'];
                $page = $t['nPage'];

                $t = $this->getScrapeValue($page, "Registrant Email:", "<");
                $data["email"] = $t['v'];
                $page = $t['nPage'];

                $domain->update($data);
                return;
            }
        }
    }

    public function shipDirectory(Request $request)
    {
        $url = "http://www.inmarsat.com/wp-content/themes/inmarsat/php/ships_api/get_ship.php";
        $master = [];
        $data = [];
        $table = 'App\\Models\\App12_ship_directory';

        $arr = $this->get3DigitAphabets('SAA', 'ZZZ');
        // 3967

        // return array_search('ZZZ', $arr)??"not found";
        foreach ($arr as $inp) {

            $postData = ["data_type"=>"A", "data_data"=>$inp];

            $resp = $this->httpPost($url, $postData);

            if(strpos($resp, "orry, no data found when searching for")){
            }else{
                $page = $resp;
                while (strpos($page, "VESSEL NAME")){
                    $data["SEARCH"] = $inp;
                    
                    $t = $this->getScrapeValue($page, "sd-ship-detail sd-first", "</div>", 2);
                    $data["VESSEL NAME"] = $t['v'];
                    $page = $t['nPage'];

                    $t = $this->getScrapeValue($page, "sd-ship-detail", "</div>", 2);
                    $data["FLAG"] = $t['v'];
                    $page = $t['nPage'];

                    $t = $this->getScrapeValue($page, "sd-ship-detail", "</div>", 2);
                    $data["TYPE"] = $t['v'];
                    $page = $t['nPage'];

                    $t = $this->getScrapeValue($page, "sd-ship-detail sd-last", "</div>", 2);
                    $data["SYSTEM"] = $t['v'];
                    $page = $t['nPage'];

                    if(strpos($page, "Numbers:") > strpos($page, "CALL SIGN:<br></span>") ){
                        $t = $this->getScrapeValue($page, "CALL SIGN:<br></span>", "</div>");
                        if(strpos($page, "CALL SIGN:<br></span>") > 0 ){
                            $data["CALL SIGN"] = $t['v'];
                            $page = $t['nPage'];
                        }
                    }

                    if(strpos($page, "Numbers:") > strpos($page, "IMO NUMBER:<br></span>") ){
                        $t = $this->getScrapeValue($page, "IMO NUMBER:<br></span>", "</div>");
                        if(strpos($page, "IMO NUMBER:<br></span>") > 0 ){
                            $data["IMO NUMBER"] = $t['v'];
                            $page = $t['nPage'];
                        }
                    }

                    if(strpos($page, "Numbers:") > strpos($page, "MMSI NUMBER:<br></span>") ){
                        $t = $this->getScrapeValue($page, "MMSI NUMBER:<br></span>", "</div>");
                        if(strpos($page, "MMSI NUMBER:<br></span>") > 0 ){
                            $data["MMSI NUMBER"] = $t['v'];
                            $page = $t['nPage'];
                        }
                    }
                    $data["Numbers"]=[];
                    While (strpos($page, "VESSEL NAME") > strpos($page, "<li>") || !strpos($page, "VESSEL NAME") && strpos($page, "<li>")){
                        $t = $this->getScrapeValue($page, "<li>", "</li>");
                        $data["Numbers"][] = $t['v'];
                        $page = $t['nPage'];
                    }
                    $data["Numbers"] = json_encode($data["Numbers"]);

                    $table::create($data);
                    $data =[];
                }
            }
        }

    }

}