<?php
namespace App\Http\Controllers;

use App\Traits\ScrapesWeb;
use Illuminate\Http\Request;

class AdminController extends CloudController
{
    use ScrapesWeb;
    protected $rtype = 'admin';
    protected $auth = 'auth:admin';

    private $logDate;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($this->rtype, $this->auth);
    }

    public function adminIndex(Request $request)
    {
        return $this->dailyLogsView($request);
    }
    
    public function dailyLogsView(Request $request)
    {
        $logDate=empty($request->date)?date('Y-m-d', time()):$request->date;
        $data = $this->getLogFileContents($logDate);
        if($request->cmd == "SaveVisitors"){
            foreach (explode('  ', $data) as $key => $log) {
                if(!strpos($log,"["))
                    continue;
                $page = $log;

                $t = $this->getScrapeValue($page, "[", "]");
                $vData["timestamp"] = $t['v'];
                $page = $t['nPage'];

                $t = $this->getScrapeValue($page, "local.INFO: ", " ");
                $vData["IP"] = $t['v'];
                $page = $t['nPage'];

                $t = $this->getScrapeValue($page, " ", ".");
                $vData["page_visited"] = $t['v'];
                $page = $t['nPage'];

                // $t = $this->getScrapeValue($page, "Registrant Name:", "<");
                // $vData["app_id"] = $t['v'];
                // $page = $t['nPage'];

                $table = $this->gtc('visitors', 13);
                $record = $table::where(['page_visited' => $vData["page_visited"], 'IP' => $vData["IP"] ])->first();
                if(empty($record)){
                    $vData["no_of_times_visited"] = 1;
                    $table::create($vData);
                }else{
                    $record->update(['no_of_times_visited' => $record['no_of_times_visited']+1]);
                }

                $vData = [];
            }
        }
        return view('c.admin.daily_logs')->with(['data' => $data??"No Data Available", 'date' => $logDate]);
    }

    public function visitorsView(Request $request)
    {
        $table = $this->gtc('visitors', 13);
        return view('c.admin.visitors')->with(['visits' => $table::paginate(10), 'page' => $request->page??1]);
    }

    public function filesUpload()
    {

    }

    public function getLogFileContents($date)
    {
        $logDate = date('Y-m-d', strtotime($date));
        $myfilepath = storage_path()."/logs/laravel-$logDate.log";
        if(file_exists($myfilepath)){
            $myfile = fopen($myfilepath, "r") or die("failed!");
            $len = filesize($myfilepath);
            $data = $len?fread($myfile,$len):"Log File Is Empty";
            fclose($myfile);
        }
        return $data??"Log File Doesn't exists.";
    }
}