<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ScrapesWeb
{
	
	public function curl_get_contents($url)
	{
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	    $output = curl_exec($ch);
	    curl_close($ch); 
	    return $output;
	}

	public function httpPost($url, $data, $cookie = "")
	{
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n".$cookie,
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ }
		return $result;
	}

	public function httpGet($url, $data = array())
	{
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'GET',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ }
		return $result;
	}

	public function get3DigitAphabets($s="AAA", $e="ZZZ")
	{
		$arr = [];
		for($i=0; $i<26; $i++){
			for($j=0; $j<26; $j++){
				for($k=0; $k<26; $k++){
					$arr[]=chr($i + 65) . chr($j + 65) . chr($k + 65);
				}
			}
		}
		$f = array_search($s, $arr);
		$len = array_search($e, $arr) - $f;
		return array_slice($arr, $f, $len);
	}

	public function strpos_rev($page, $search, $pos = -1)
	{
		$pos = $pos==-1?strlen($page):$pos;
		$vStart=strpos($page, $search, 1);
		$i=0;
		while( $vStart < $pos ){
			$tp = strpos($page, $search, $vStart);
			if($tp > $vStart){
				$vStart = $tp;
			}else{
				break;
			}
			$i++;
			if($i=10){
				break;
			}
		}
        return $vStart;
	}

	public function getScrapeValue($page, $search_start, $search_end, $offset = 0)
	{
		if(!strpos($page, $search_start)){
			return ['v' => "", 'nPage' => $page];
		}else{
			$vStart = strpos($page, $search_start) + strlen($search_start) + $offset;
	        $vEnd = strpos($page, $search_end, $vStart);
	        $v = substr($page, $vStart, $vEnd - $vStart);
	        $nPage = substr($page, $vEnd, strlen($page));
	        return ['v' => trim($v), 'nPage' => $nPage];
		}
	}

	public function getPageByBounds($page, $search, $fbound, $lbound)
	{
		$sPos = strpos($page, $search);
		$vStart=$this->strpos_rev($page, $fbound, $sPos);
        $vEnd = strpos($page, $lbound, $sPos) + strlen($lbound);
        $v = substr($page, $vStart, $vEnd - $vStart);
        return trim($v);
	}

	public function checkLimits()
	{
		if(empty($request->start_at) || empty($request->end_at)){
            if(is_numeric($request->start_at) || is_numeric($request->end_at)){
                return false;
            }
        }
        return true;
	}

}