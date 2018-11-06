<?php

namespace App\Traits\Scraps;

use Illuminate\Http\Request;

trait BarkDotCom
{
	public function fill_bark_urls_cat_id()
    {
        $url = "https://www.bark.com/en/gb/category/?q=";

        $table = $this->gtc('bark_urls', 12);

        foreach ($table::all() as $record) {
            $resp = $this->curl_get_contents($url . str_replace(" ","%20",$record->category_name));
            $data = json_decode($resp, true);
            $record->update([
            	"category_id" => $data['values']['category_list'][0]['id'],
            ]);
        }
    }

    public function get_q_n_a_from_bark_dom_com()
    {
        $url = "https://www.bark.com/validate-project/";
        $table = $this->gtc('bark_urls', 12);
        $table2 = $this->gtc('bark_q_a', 12);
        foreach ($table::all() as $record) {

            $postData = [
                "category_id" => $record->category_id, 
                "postcode_id" => 447831, 
                "postcode_type" => "p", 
                "bark_mode" => "home", 
                "exp_ph" => "0", 
                "category_name" => $record->category_name, 
            ];
            $cookie = "Cookie: PHPSESSID=6qdoc39fumsqu26pj71lqsknb3\r\n";
            $resp = $this->httpPost($url, $postData, $cookie);
            $data = json_decode($resp, true);
            if($data['values']['parentCategoryName'] == null){
                if(is_array($data['values']['categories'][$record->category_id]['custom_fields'])){
                    foreach ($data['values']['categories'][$record->category_id]['custom_fields'] as $value) {
                        if(isset($value['options'])){
                            $options = [];
                            foreach ($value['options'] as $option) {
                                $options[]=$option['label'];
                            }
                        }else{
                            $options = "N/A";
                        }
                        $table2::create([
                            "parent_category" => $data['values']['parentCategoryName'],
                            "category_id" => $record->category_id,
                            "category_name" => $record->category_name,
                            "url" => $url,
                            "question" => $value['label'],
                            "options" => json_encode($options),
                        ]);
                    }
                }
            }else{
                if(is_array($data['values']['categories'])){
                    foreach ($data['values']['categories'] as $category_id => $category) {
                        if(is_array($data['values']['categories'][$category_id]['custom_fields'])){
                            foreach ($data['values']['categories'][$category_id]['custom_fields'] as $value) {
                                if(isset($value['options'])){
                                    $options = [];
                                    foreach ($value['options'] as $option) {
                                        $options[]=$option['label'];
                                    }
                                }else{
                                    $options = "N/A";
                                }
                                $table2::create([
                                    "parent_category" => $data['values']['parentCategoryName'],
                                    "category_id" => $category_id,
                                    "category_name" => $category['name'],
                                    "url" => $url,
                                    "question" => $value['label'],
                                    "options" => json_encode($options),
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}