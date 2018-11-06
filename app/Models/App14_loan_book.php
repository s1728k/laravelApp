<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App14_loan_book extends Model
{
public $table = 'app14_loan_book';
protected $fillable = ['account_name', 'principal', 'roi', 'tenure', 'tenure_unit', 'emi', 'disbursement_date', 'commensement_date', 'processing_fees', 'loan_provider_id', 'loan_receiver_id'];
protected $hidden = [''];
}
