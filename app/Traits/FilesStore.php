<?php

namespace App\Traits;

use ZipArchive;
use App\File;
use Illuminate\Http\Request;

trait FilesStore
{
    public function exportAppsToCSV()
    {
        \Log::Info($this->fc.'exportAppsToCSV');
        // $table = 'App\\'.ucwords(rtrim('apps','s'));
        // $records = json_decode($table::all(), true);
        // $this->saveArrayInCSV($records, 'apps');
        // $this->downloadFile('apps');
    }

    public function importFromCSV()
    {
        \Log::Info($this->fc.'importFromCSV');
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
        \Log::Info($this->fc.'filesView');
        $files = File::where('app_id', $this->app_id)->paginate(10);
        $size = File::where('app_id', $this->app_id)->sum('size');
        return view($this->theme.'.file.files_store')->with([
            'files' => $files??[], 
            'page'=>$request->page??1,
            'size' => round($size/1024/1024,2),
        ]);
    }

    public function uploadFile(Request $request)
    {
        \Log::Info($this->fc.'uploadFile');
        $request->validate([
            'file' => 'file|max:20000',
        ]);
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
        \Log::Info($this->fc.'uploadFiles');
        $request->validate([
            'file.*' => 'file|max:20000',
        ]);
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
        \Log::Info($this->fc.'downloadFile');
        $table = 'App\\File';
        $file = $table::findOrFail($id);
        if($file->app_id != $this->app_id){
            return "";
        }
        return response()->download(base_path().str_replace(env('APP_URL'),'',$file->path));
    }

    public function downloadFile1($id)
    {
        \Log::Info($this->fc.'downloadFile1');
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
        \Log::Info($this->fc.'replaceFile');
        $request->validate([
            'id' => 'required|integer',
            'file' => 'file',
        ]);
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
        \Log::Info($this->fc.'deleteFile');
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
        \Log::Info($this->fc.'importCreateCSV');
        $request->validate([
            'table' => 'required|string',
            'createCSV' => 'file|mimes:csv,txt',
        ]);
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
        $request->validate([
            '_token' => [function($attribute, $value, $fail){
                $fail("Data successfully created.");
            }],
        ]);
    }

    public function importUpdateCSV(Request $request)
    {
        \Log::Info($this->fc.'importUpdateCSV');
        $request->validate([
            'table' => 'required|string',
            'updateCSV' => 'file|mimes:csv,txt',
        ]);
        $table = $this->gtc($request->table);
        $path = storage_path() .'/app/'. $request->file('updateCSV')->store('');
        $file = fopen($path,"r");
        if(! feof($file))
            $keys = fgetcsv($file);
        while(! feof($file))
        {
            $t=fgetcsv($file);
            if(is_array($t)){
                $record = array_combine($keys, $t);
                if(!empty($record['id'])){
                    $table::findOrFail($record['id'])->update($record);
                }
            }
        }
        $t=fgetcsv($file);
        if(is_array($t)){
            $record = array_combine($keys, $t);
            if(!empty($record['id'])){
                $table::findOrFail($record['id'])->update($record);
            }
        }
        fclose($file);
        $request->validate([
            '_token' => [function($attribute, $value, $fail){
                $fail("Data successfully updated.");
            }],
        ]);
    }

    public function importCreateJSON(Request $request)
    {
        \Log::Info($this->fc.'importCreateJSON');
        $request->validate([
            'table' => 'required|string',
            'createJSON' => 'file|mimes:txt,json',
        ]);
        $table = $this->gtc($request->table);
        $path = storage_path() .'/app/'. $request->file('createJSON')->store('');
        $file = fopen($path,"r");
        $data = fread($file,filesize($path));
        fclose($file);
        $arr = json_decode($data, true);
        foreach ($arr as $record) {
            $table::create($record);
        }
        $request->validate([
            '_token' => [function($attribute, $value, $fail){
                $fail("Data successfully created.");
            }],
        ]);
    }

    public function importUpdateJSON(Request $request)
    {
        \Log::Info($this->fc.'importUpdateJSON');
        $request->validate([
            'table' => 'required|string',
            'updateJSON' => 'file|mimes:txt,json',
        ]);
        $table = $this->gtc($request->table);
        $path = storage_path() .'/app/'. $request->file('updateJSON')->store('');
        $file = fopen($path,"r");
        $data = fread($file,filesize($path));
        fclose($file);
        $arr = json_decode($data, true);
        foreach ($arr as $record) {
            $table::update($record);
        }
        $request->validate([
            '_token' => [function($attribute, $value, $fail){
                $fail("Data successfully updated.");
            }],
        ]);
    }

    public function exportToCSV($table_name)
    {
        \Log::Info($this->fc.'exportToCSV');
        $records = $this->getTableArray($table_name);
        $this->saveArrayInCSV($records, $table_name, '.csv');
        $this->exportFile($table_name. '.csv');
    }

    public function exportToTXT($table_name)
    {
        \Log::Info($this->fc.'exportToTXT');
        $records = $this->getTableArray($table_name);
        $this->saveArrayInCSV($records, $table_name, '.txt');
        $this->exportFile($table_name. '.txt');
    }

    public function exportToJSON($table_name)
    {
        \Log::Info($this->fc.'exportToJSON');
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
        \Log::Info($this->fc.'exportFile');
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
        \Log::Info($this->fc.'getFile');
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