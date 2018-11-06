<?php

namespace App\Traits\Scraps;

use Illuminate\Http\Request;

trait bar_code_lookup_dot_com
{
    public function gtin_series_generator()
    {
        $table = $this->gtc('gtin', 12);
        $f = $table::latest()->first();
        for($i= $f->gtin+1; $i<100000000; $i++){
            if($this->IsValidGtin($i)){
                $table::create(['gtin'=>$i]);
            }
        }
    }

    public function IsValidGtin($code)
    {
        if (!is_numeric($code))
        {
            return false;
        }
        switch (strlen($code))
        {
            case 8:
                $code = "000000" . $code;
                break;
            case 12:
                $code = "00" . $code;
                break;
            case 13:
                $code = "0" . $code;
                break;
            case 14:
                break;
            default:
                // wrong number of digits
                return false;
        }
        // calculate check digit
        $a = str_split($code);
        $sum = 0;   
        for($i=0; $i<=12; $i++){
            if($i % 2 == 0){
                $a[$i] = $a[$i] * 3;
            }
            $sum = $sum + $a[$i];
        }
        $check = (10 - ($sum % 10)) % 10;
        // evaluate check digit
        $last = $a[13];
        return $check == $last;
    }
}