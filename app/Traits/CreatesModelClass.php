<?php

namespace App\Traits;

trait CreatesModelClass
{

	public function createModelClass($table, $authenticatable = false, $fillable = null, $hidden = null)
	{
        \Log::Info(request()->ip()." created model class for table ".$table. " for app id ".$this->app_id);

        $myFilePath = app_path() ."/Models/".$this->tClass($table).".php";
        $myfile = fopen($myFilePath, "w");
        \Log::Info($myFilePath);

		$fillable = $fillable??$this->getFields($table, ['id', 'created_at', 'updated_at', 'created_at and updated_at', 'remember_token']);
        $hidden = $hidden??[];

        $cont = "<?php\n";

        $cont = $cont . "namespace App\Models;\n";
        if($authenticatable){
            $cont = $cont . "use Illuminate\Foundation\Auth\User as Authenticatable;\n";
            $cont = $cont . "use Illuminate\Notifications\Notifiable;\n";

            $cont = $cont . "class ".$this->tClass($table)." extends Authenticatable\n";
        }else{
            $cont = $cont . "use Illuminate\Database\Eloquent\Model;\n";
            $cont = $cont . "class ".$this->tClass($table)." extends Model\n";
        }
        $cont = $cont . "{\n";
        $cont = $authenticatable? $cont . "use Notifiable;\n" : $cont;
        $cont = $cont . "public $" ."table = '" .'app'.$this->app_id.'_'. $table."';\n";
        $cont = $cont . "protected $" ."connection = '" .$this->con."';\n";
        $cont = $cont . "protected $" ."fillable = ['". implode("', '", $fillable) ."'];\n";
        
        if($authenticatable){
            $cont = $cont . "protected $" ."hidden = ['password', 'remember_token',];\n";
        }else{
            $cont = $cont . "protected $" ."hidden = ['". implode("', '", $hidden) ."'];\n";
        }
        $cont = $cont . "}\n";

        fwrite($myfile, $cont);
        fclose($myfile);
	}

	public function deleteModelClass($table)
	{
        \Log::Info(request()->ip()." deleted model class for table ".$table. " for app id ".$this->app_id);
		$myfile = app_path() ."/Models/".$this->tClass($table) .".php";
        if(is_writable($myfile)){
            unlink($myfile);
            return ['status' => 'success'];
        }else{
            return ['status' => 'failed'];
        }
	}

    private function getModelClass()
    {
        $m = "<?php\nnamespace App\Models;\nuse Illuminate\Database\Eloquent\Model;\nclass %MCN% extends Model\n{\n";
        $m = $m."public $" ."table = '" .'app'.$this->app_id.'_'. $table."';\n";
    }
}