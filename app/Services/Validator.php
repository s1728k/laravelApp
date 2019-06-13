<?php

namespace App\Services;

class Validator extends \Illuminate\Validation\Validator{

	private $_custom_messages = array(
        "non_fraction" => "value must be whole number",
        "year" => "The value must be between 1901 to 2155 or 0000",
	    "tiny_integer" => "overflow - value must be between -128 to 127",
	    "tiny_integer_unsigned" => "overflow - value must be between 0 to 255",
        "small_integer" => "overflow - value must be between -32,768 to 32,767",
        "small_integer_unsigned" => "overflow - value must be between 0 to 65,535",
        "medium_integer" => "overflow - value must be between -8,388,608 to 8,388,607",
        "medium_integer_unsigned" => "overflow - value must be between 0 to 16,777,215",
        "integer_custom" => "overflow - value must be between -2,147,483,648 to 2,147,483,647",
        "integer_custom_unsigned" => "overflow - value must be between 0 to 4,294,967,295",
        "big_integer" => "overflow - value must be between -9,223,372,036,854,775,808 to 9,223,372,036,854,775,807",
        "big_integer_unsigned" => "overflow - value must be between 0 to 18,446,744,073,709,551,615",
        "decimal" => "The value must have maximum 8 digits and 2 decimals",
        "char" => "Char type must be assigned a fixed string length",
        "date_multi_format" => "invalid format.",
        "field_param" => "error",
	);

	public function __construct( $translator, $data, $rules, $messages = array(), $customAttributes = array() ) {
        parent::__construct( $translator, $data, $rules, $messages, $customAttributes );
        $this->_set_custom_stuff();
    }

    protected function _set_custom_stuff() {
        // $this->setCustomMessages( $this->_custom_messages );
    }

    protected function validateNonFraction( $attribute, $value ) {     
        if( !strpos($value, ".") || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateYear( $attribute, $value ) {     
        if( $value >= 1901 && $value <= 2155 || $value == 0 || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateTinyInteger( $attribute, $value ) {     
        if( $value>=-128 && $value<= 127 || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateTinyIntegerUnsigned( $attribute, $value ) {     
        if( $value>=0 && $value<= 255 || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateSmallInteger( $attribute, $value ) {     
        if( $value>=-32768 && $value<= 32767 || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateSmallIntegerUnsigned( $attribute, $value ) {     
        if( $value>=0 && $value<= 65535 || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateMediumInteger( $attribute, $value ) {     
        if( $value>=-8388608 && $value<= 8388607 || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateMediumIntegerUnsigned( $attribute, $value ) {     
        if( $value>=0 && $value<= 16777215 || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateIntegerCustom( $attribute, $value ) {     
        if( $value>=-2147483648 && $value<= 2147483647 || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateIntegerCustomUnsigned( $attribute, $value ) {     
        if( $value>=0 && $value<= 4294967295 || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateBigInteger( $attribute, $value ) {     
        if( $value>=-9223372036854775808 && $value<= 9223372036854775807 || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateBigIntegerUnsigned( $attribute, $value ) {     
        if( $value>=0 && $value<= 18446744073709551615 || empty($value) ){
            return true;
        } else {
            return false;
        }
    }

    protected function validateDateMultiFormat( $attribute, $value, $formats ) {     
        foreach($formats as $format) {
            $parsed = date_parse_from_format($format, $value);
            if ($parsed['error_count'] === 0 && $parsed['warning_count'] === 0 || empty($value) ) {
              return true;
            }
        }
        return false;
    }

    protected function validateDecimal( $attribute, $value, $parameters, $validator)
    {
        \Log::Info('validateDecimal');
        $validator->addReplacer('decimal', function($message, $attribute, $rule, $parameters){
            return str_replace([8,2],[$parameters[0],$parameters[1]],$message);
        });
        if( empty($value) ){return true;}
        if( strlen(str_replace('.','',$value))<=$parameters[0] ){
            if(!strpos('0'.$value,".")){
                return true;
            }else{
                $t=explode(".",$value);
                if(strlen($t[1])<=$parameters[1]){
                    return true;
                }else{
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    protected function validateChar( $attribute, $value, $parameters, $validator)
    {
        if( strlen(str_replace(' ','',$value))==$parameters[0] || empty($value) ){
            return true;
        } else {
            $validator->addReplacer('char', function($message, $attribute, $rule, $parameters){
                return "The char must be a fixed string of length ".$parameters[0];
            });
            return false;
        }
    }

    protected function validateFieldParam( $attribute, $value, $parameters, $validator)
    {
        if( empty($value) ){return true;}
        if(in_array($request->field_type[$key], ['char','string'])){
            if(is_numeric($value)){
                if($value>21844){
                    $fail('String length must not be more than 21844.');
                }else if( strpos($value, ".") ){
                    $fail('String length must be whole number.');
                }
            }else {
                $fail('String length must be numeric.');
            } 
        }else if(in_array($request->field_type[$key], ['decimal','unsignedDecimal','float'])){
            if( strpos($value, ",") ){
                $t=explode(',',$value);
                if(is_numeric($t[0]) && is_numeric($t[1])){
                    if($t[0]>65){
                        $fail('Real type M(total digits) must not be more than 65.');
                    }else if( $t[1]>30 ){
                        $fail('Real type D(decimals) must not be more than 30.');
                    }else if( strpos($t[0], ".") || strpos($t[1], ".") ){
                        $fail('Real type must have M & D values as whole number.');
                    }
                }else{
                    $fail('Real type must have numeric lengths.');
                }
            }else{
                $fail('Real type must have both M(total digits), D(decimals).');
            }
        }else if($request->field_type[$key] == 'enum'){

        }
    }
}