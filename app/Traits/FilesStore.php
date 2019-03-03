<?php

namespace App\Traits;

use ZipArchive;
use App\File;
use Illuminate\Http\Request;

trait FilesStore
{
    public function exportAppsToCSV()
    {
        // $table = 'App\\'.ucwords(rtrim('apps','s'));
        // $records = json_decode($table::all(), true);
        // $this->saveArrayInCSV($records, 'apps');
        // $this->downloadFile('apps');
    }

    public function importFromCSV()
    {
        // $file = fopen("contacts.csv","r");
        // while(! feof($file))
        // {
        //   print_r(fgetcsv($file));
        // }
        // print_r(fgetcsv($file));
        // fclose($file);
//         $extension = $request->file('import_file')->getClientOriginalExtension();
// $filename = uniqid().'.'.$extension; 
// Storage::disk('local')->putFileAs('/files/', $request->file('import_file'), $filename);
    }

    public function filesView()
    {
        \Log::Info(request()->ip()." visited files page for app id ".$this->app_id);
        $files = File::where('app_id', $this->app_id)->paginate(10);
        return view($this->theme.'.file.files_store')->with(['files' => $files??[]]);
    }

    public function uploadFile(Request $request)
    {
        \Log::Info(request()->ip()." uploaded file for app id ".$this->app_id);
        $path = '/storage/app/'.$request->file('file')->store('file_store');
        $table = 'App\\File';
        $file = $table::create([
            'app_id' => \Auth::user()->app_id,
            'name' => $request->file->getClientOriginalName(),
            'mime' => $request->file->getMimeType(),
            'size' => $request->file->getSize(),
            'path' => $path,
        ]);
        return $file;
    }

    public function uploadFiles(Request $request)
    {
        \Log::Info(request()->ip()." uploaded files for app id ".$this->app_id);
        $files = $request->file('files');
        $res = [];
        if($request->hasFile('files'))
        {
            $table = 'App\\File';
            foreach ($files as $key => $file) {
                $path = '/storage/app/'.$file->store('file_store');
                $res[] = $table::create([
                    'app_id' => $this->app_id,
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'path' => $path,
                ]);
            }
        }
        $request->validate(['success' => 'required']);
    }

    public function downloadFile($id)
    {
        \Log::Info(request()->ip()." downloaded file for app id ".$this->app_id);
        $table = 'App\\File';
        $file = $table::findOrFail($id);
        if($file->app_id != $this->app_id){
            return "";
        }
        return response()->download(base_path().str_replace(env('APP_URL'),'',$file->path));
    }

    public function downloadFile1($id)
    {
        $zip = new ZipArchive();
        $zip->open('file.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $table = 'App\\File';
        foreach ($table::all() as $file) {
            $zip->addFile($file->path, base_path());
        }
        $zip->close();
        return response()->download('file.zip');
    }

    public function replaceFile(Request $request)
    {
        \Log::Info(request()->ip()." updated file for app id ".$this->app_id);
        $path = $request->file('file')->store('public');
        $table = 'App\\File';
        $file = $table::findOrFail($request->id);
        if($file->app_id != $this->app_id){
            return ['status' => 'un-authorized'];
        }
        // return ['status' => 'un-authorized'];
        $file_path = str_replace(env('APP_URL'),'',$file->path);
        if(is_writable(base_path().$file_path)){
            unlink(base_path().$file_path);
        }
        $file->update([
            'name' => $request->file->getClientOriginalName(),
            'mime' => $request->file->getMimeType(),
            'size' => $request->file->getSize(),
            'path' => env('APP_URL').str_replace('public','/public/storage',$path),
        ]);
        $request->validate(['success' => 'required']);
    }

    public function deleteFile(Request $request)
    {
        \Log::Info(request()->ip()." updated file for app id ".$this->app_id);
        $table = 'App\\File';
        $file = $table::findOrFail($request->id);
        if($file->app_id != $this->app_id){
            return ['status' => 'un-authorized'];
        }
        $file_path = str_replace(env('APP_URL'),'',$file->path);
        if(is_writable(base_path().$file_path)){
            unlink(base_path().$file_path);
        }
        if($table::destroy($request->id)){
            return ['status' => 'success'];
        }
        return ['status' => 'un-successfull'];
    }

    public function importCreateCSV(Request $request)
    {
        \Log::Info(request()->ip()." imported csv for create for app id ".$this->app_id);
        $table = $this->gtc($request->table);
        $path = storage_path() .'/app/'. $request->file('createCSV')->store('');
        $file = fopen($path,"r");
        if(! feof($file))
            $keys = fgetcsv($file);
        while(! feof($file))
        {
            $t=fgetcsv($file);
            if(is_array($t)){
                $table::create(array_combine($keys, $t));
            }
        }
        $t=fgetcsv($file);
        if(is_array($t)){
            $table::create(array_combine($keys, $t));
        }
        fclose($file);
        return ['status' => 'success'];
    }

    public function importUpdateCSV(Request $request)
    {
        \Log::Info(request()->ip()." imported csv for update for app id ".$this->app_id);
        $table = $this->gtc($request->table);
        $path = storage_path() .'/app/'. $request->file('updateCSV')->store('');
        $file = fopen($path,"r");
        if(! feof($file))
            $keys = fgetcsv($file);
        while(! feof($file))
        {
            $t=fgetcsv($file);
            if(is_array($t)){
                $table::update(array_combine($keys, $t));
            }
        }
        $t=fgetcsv($file);
        if(is_array($t)){
            $table::update(array_combine($keys, $t));
        }
        fclose($file);
        return ['status' => 'success'];
    }

    public function importCreateJSON(Request $request)
    {
        \Log::Info(request()->ip()." imported json for create for app id ".$this->app_id);
        $table = $this->gtc($request->table);
        $path = storage_path() .'/app/'. $request->file('createJSON')->store('');
        $file = fopen($path,"r");
        $data = fread($file,filesize($path));
        fclose($file);
        $arr = json_decode($data, true);
        foreach ($arr as $record) {
            $table::create($record);
        }
        // return ['status' => 'success'];
        return $this->myTableListView();
    }

    public function importUpdateJSON(Request $request)
    {
        \Log::Info(request()->ip()." imported json for update for app id ".$this->app_id);
        $table = $this->gtc($request->table);
        $path = storage_path() .'/app/'. $request->file('updateJSON')->store('');
        $file = fopen($path,"r");
        $data = fread($file,filesize($path));
        fclose($file);
        $arr = json_decode($data, true);
        foreach ($arr as $record) {
            $table::update($record);
        }
        return ['status' => 'success'];
    }

    public function exportToCSV($table_name)
    {
        \Log::Info(request()->ip()." exported csv for for app id ".$this->app_id);
        $records = $this->getTableArray($table_name);
        $this->saveArrayInCSV($records, $table_name, '.csv');
        $this->exportFile($table_name. '.csv');
    }

    public function exportToTXT($table_name)
    {
        \Log::Info(request()->ip()." exported TXT for for app id ".$this->app_id);
        $records = $this->getTableArray($table_name);
        $this->saveArrayInCSV($records, $table_name, '.txt');
        $this->exportFile($table_name. '.txt');
    }

    public function exportToJSON($table_name)
    {
        \Log::Info(request()->ip()." exported JSON for for app id ".$this->app_id);
        $records = $this->getTableArray($table_name);
        $myfile = fopen(storage_path()."/temp/".$table_name.'.json', "w");
        fwrite($myfile, json_encode($records));
        fclose($myfile);
        $this->exportFile($table_name. '.json');
    }

    private function getTableArray($table_name)
    {
        $table = $this->gtc($table_name);
        return json_decode($table::all(), true);
    }

    private function saveArrayInCSV($array, $table_name, $fext)
    {
        $myfile = fopen(storage_path()."/temp/".$table_name.$fext, "w");
        fputcsv($myfile, $this->getNonHiddenFields('app'.$this->app_id.'_'.$table_name));
        foreach ($array as $fields) {
            fputcsv($myfile, $fields);
        }
        fclose($myfile);
    }

    private function exportFile($file_name)
    {
        $file_path = storage_path()."/temp/".$file_name;
        if (is_writable($file_path)) {
            header('Content-Type: application/octet-stream');
            // header('Content-Type: application/x-msdownload');
            header('Content-Disposition: attachment; filename="'.$file_name.'"');
            header("Pragma: public");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            // header('Expires: 0');
            // header('Content-Length: ' . filesize($file_path));
            // readfile($file_path);
            set_time_limit(0);
            $file = @fopen($file_path,"rb");
            while(!feof($file))
            {
                print(@fread($file, 1024*8));
                ob_flush();
                flush();
            }
            // mod_xsendfile
        }
    }

    public function getFile($pivot_table, $pivot_field, $pivot_id)
    {
        \Log::Info(request()->ip()." read the file for for app id ".$this->app_id);
        $path = storage_path() .'/app/'. $this->request->file('uploadFile')->store($pivot_table.'/'.$pivot_field.'/'.$pivot_id);

        $this->setTable('attach');
        $id = $this->table::create([
            'pivot_table' => $pivot_table, 
            'pivot_field' => $pivot_field, 
            'pivot_id' => $pivot_id, 
            // 'attach_type_id' => $attach_type_id, 
            // 'attach_name' => $attach_name, 
            'path' => $path
        ])->id;

        return ['id' => $id , 'path'=> $path];
    }
}