<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ValidationMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$messages = [ 
            'accepted' => 'The :attribute must be accepted.',
            'active_url' => 'The :attribute is not a valid URL.',
            'after' => 'The :attribute must be a date after :date.',
            'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
            'alpha' => 'The :attribute may only contain letters.',
            'alpha_dash' => 'The :attribute may only contain letters, numbers, and dashes.',
            'alpha_num' => 'The :attribute may only contain letters and numbers.',
            'array' => 'The :attribute must be an array.',
            'before' => 'The :attribute must be a date before :date.',
            'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
            'between[numeric]' => 'The :attribute must be between :min and :max.',
            'between[file]' => 'The :attribute must be between :min and :max kilobytes.',
            'between[string]' => 'The :attribute must be between :min and :max characters.',
            'between[array]' => 'The :attribute must have between :min and :max items.',
            'boolean' => 'The :attribute field must be true or false.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'date' => 'The :attribute is not a valid date.',
            'date_format' => 'The :attribute does not match the format :format.',
            'different' => 'The :attribute and :other must be different.',
            'digits' => 'The :attribute must be :digits digits.',
            'digits_between' => 'The :attribute must be between :min and :max digits.',
            'dimensions' => 'The :attribute has invalid image dimensions.',
            'distinct' => 'The :attribute field has a duplicate value.',
            'email' => 'The :attribute must be a valid email address.',
            'exists' => 'The selected :attribute is invalid.',
            'file' => 'The :attribute must be a file.',
            'filled' => 'The :attribute field must have a value.',
            'image' => 'The :attribute must be an image.',
            'in' => 'The selected :attribute is invalid.',
            'in_array' => 'The :attribute field does not exist in :other.',
            'integer' => 'The :attribute must be an integer.',
            'ip' => 'The :attribute must be a valid IP address.',
            'ipv4' => 'The :attribute must be a valid IPv4 address.',
            'ipv6' => 'The :attribute must be a valid IPv6 address.',
            'json' => 'The :attribute must be a valid JSON string.',
            'max[numeric]' => 'The :attribute may not be greater than :max.',
            'max[file]' => 'The :attribute may not be greater than :max kilobytes.',
            'max[string]' => 'The :attribute may not be greater than :max characters.',
            'max[array]' => 'The :attribute may not have more than :max items.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'mimetypes' => 'The :attribute must be a file of type: :values.',
            'min[numeric]' => 'The :attribute must be at least :min.',
            'min[file]' => 'The :attribute must be at least :min kilobytes.',
            'min[string]' => 'The :attribute must be at least :min characters.',
            'min[array]' => 'The :attribute must have at least :min items.',
            'not_in' => 'The selected :attribute is invalid.',
            'numeric' => 'The :attribute must be a number.',
            'present' => 'The :attribute field must be present.',
            'regex' => 'The :attribute format is invalid.',
            'required' => 'The :attribute field is required.',
            'required_if' => 'The :attribute field is required when :other is :value.',
            'required_unless' => 'The :attribute field is required unless :other is in :values.',
            'required_with' => 'The :attribute field is required when :values is present.',
            'required_with_all' => 'The :attribute field is required when :values is present.',
            'required_without' => 'The :attribute field is required when :values is not present.',
            'required_without_all' => 'The :attribute field is required when none of :values are present.',
            'same' => 'The :attribute and :other must match.',
            'size[numeric]' => 'The :attribute must be :size.',
            'size[file]' => 'The :attribute must be :size kilobytes.',
            'size[string]' => 'The :attribute must be :size characters.',
            'size[array]' => 'The :attribute must contain :size items.',
            'string' => 'The :attribute must be a string.',
            'timezone' => 'The :attribute must be a valid zone.',
            'unique' => 'The :attribute has already been taken.',
            'uploaded' => 'The :attribute failed to upload.',
            'url' => 'The :attribute format is invalid.',
        ];

        foreach ($messages as $rule => $message) {
            DB::table('validation_messages')->insert(['rule'=>$rule, 'error_message'=>$message]);
        }

        $_custom_messages = [
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
        ];

        foreach ($_custom_messages as $rule => $message) {
            DB::table('validation_messages')->insert(['app_id'=>0, 'rule'=>$rule, 'error_message'=>$message]);
        }
    }
}
