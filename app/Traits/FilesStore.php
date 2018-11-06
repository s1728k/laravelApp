<?php

namespace App\Traits;

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
        $files = File::paginate(10);
        $tables = $this->getTables();
        $fields = $this->getRemovableFields($tables[0]);
        return view($this->theme.'.file.files_store')->with(['files' => $files??[], 'tables' => $tables, 'fields' => $fields]);
    }

    public function uploadFile(Request $request)
    {
        \Log::Info(request()->ip()." uploaded file for app id ".$this->app_id);
        $path = storage_path() .'/app/'. $request->file('file')->store($request->pivot_table.'/'.$request->pivot_field);
        $request->validate([
            'pivot_table' => [function($attribule, $value, $fail)use($request){
                $table = 'App\\File';
                $file = $table::where([
                    'pivot_table'=>$request->pivot_table, 
                    'pivot_field'=>$request->pivot_field, 
                    'pivot_id'=>$request->pivot_id, 
                    'sr_no'=>1
                ])->first();
                if(!empty($file))
                    $fail("Duplicate Pivot Reference");
            }],
        ]);
        $table = 'App\\File';
        $table::create([
            'name' => $request->file->getClientOriginalName(),
            'mime' => $request->file->getMimeType(),
            'size' => $request->file->getSize(),
            'pivot_table' => $request->pivot_table,
            'pivot_field' => $request->pivot_field,
            'pivot_id' => $request->pivot_id,
            'sr_no' => 1,
            'path' => $path,
        ]);
        $request->validate(['success' => 'required']);
    }

    public function uploadFiles(Request $request)
    {
        \Log::Info(request()->ip()." uploaded files for app id ".$this->app_id);
        $files = $request->file('files');
        if($request->hasFile('files'))
        {
            $table = 'App\\File';
            foreach ($files as $key => $file) {
                $path = storage_path() .'/app/'. $file->store($request->pivot_table.'/'.$request->pivot_field);
                $table::create([
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'pivot_table' => $request->pivot_table,
                    'pivot_field' => $request->pivot_field,
                    'pivot_id' => $request->pivot_id,
                    'sr_no' => $key+1,
                    'path' => $path,
                ]);
            }
        }
        $request->validate(['success' => 'required']);
    }

    public function downloadFile($pivot_table, $pivot_field, $pivot_id, $sr_no = 1)
    {
        \Log::Info(request()->ip()." downloaded file for app id ".$this->app_id);
        $table = 'App\\File';
        $file = $table::where([
            'pivot_table'=>$pivot_table, 
            'pivot_field'=>$pivot_field, 
            'pivot_id'=>$pivot_id, 
            'sr_no'=>$sr_no
        ])->first();
        return response()->download($file->path, $file->name);
    }

    public function replaceFile(Request $request)
    {
        \Log::Info(request()->ip()." updated file for app id ".$this->app_id);
        $path = storage_path() .'/app/'. $request->file('file')->store($request->pivot_table.'/'.$request->pivot_field);
        $table = 'App\\File';
        $file = $table::where([
            'pivot_table'=>$request->pivot_table, 
            'pivot_field'=>$request->pivot_field, 
            'pivot_id'=>$request->pivot_id, 
            'sr_no'=>$request->sr_no
        ])->first();
        $file->update([
            'name' => $request->file->getClientOriginalName(),
            'mime' => $request->file->getMimeType(),
            'size' => $request->file->getSize(),
            'path' => $path,
        ]);
        if(is_writable($file->path)){
            unlink($file->path);
        }
        $request->validate(['success' => 'required']);
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