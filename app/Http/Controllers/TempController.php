<?php
namespace App\Http\Controllers;

use Config;
use App\Session;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use App\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

class TempController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function up()
    {
        // Schema::create('a123', function (Blueprint $table) {
        //     $length = "";
        //     $length = $length ?: Builder::$defaultStringLength;

        //     $table->addColumn('string', "name", compact('length'));
        //     // $table->addColumn("string", "name");
        // });
        // Schema::dropIfExists('queries');
        
        // Schema::create('queries', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('app_id')->unsigned()->index();
        //     $table->string("name");
        //     $table->string("auth_providers");
        //     $table->string("tables");
        //     $table->string("commands");
        //     $table->text("fillables")->nullable();
        //     $table->text("hiddens")->nullable();
        //     $table->text("mandatory")->nullable();
        //     $table->text("joins")->nullable();
        //     $table->text("filters")->nullable();
        //     $table->string("specials")->nullable();
        //     $table->timestamps();
        // });

        // Schema::dropIfExists('files');

        // Schema::create('files', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->unsignedInteger('app_id');
        //     $table->string('name');
        //     $table->string('mime');
        //     $table->unsignedInteger('size');
        //     $table->string('path');
        //     $table->timestamps();
        // });
    }


    public function down()
    {
        // Schema::dropIfExists('tourist');
    }

    public function upd()
    {
    //     $url = "https://www.indeed.com/resumes/rpc/preview?keys=3238380b92443418%3B89188ee8ed0c9ed1%3Be4535c81f19c681f%3B7ad993b277f6fa30%3Bb376b75098b6e587%3B4d45e8f8bf7030cd%3Bf75627ae7a63bb0e%3B1ef7ade1593d732c%3B9d924ff66679eb67%3B739f4fc9be934c37%3B52f87985ec3c9db4%3B82d8cf5265cfce12%3Bff23afea5398050f%3B863d648f6a573307%3Bb1e994fdccdd7eac%3B68091341788c09b2%3Badf4c2318bce70b6%3Bd187244e61a5a772%3B462279c5123f4a06%3B8e33e87637ec6161%3Bc4bd6d3e6693d15c%3Bbe76e13f672b9a79%3B4a4a9084f327972e%3B8b11fd3fd647b514%3Ba7711a7fbe26cdf7%3B846c1df613c7a823%3Bacc39ec76cc9b7e3%3Bf35630f3cae12c52%3B7c80a66b09afb86b%3B2ede63749fc19c0a%3B6c28dc314dab48ec%3B2ca03a3440648ddb%3B1b459354e407cd94%3Bbc06dc36b6e71059%3Bd30049a2272d9f0d%3B3ac7204323b51479%3B941979723aa37d8d%3B14038152dd76ba92%3B55dbf42b059ef259%3B681a16a50a0e4b06%3B71d14e4af44fc06e%3Bbcf58b050e1526b0%3B54afe8509403600c%3Bd2aa5ef47da481e3%3B5c91feaa6579b5eb%3Bee82e22e1ae584e2%3B55a6092e53c18d3a%3Bc4e4c75402e3fcc0%3B0eca8381821983db%3B00d46d4fee901f73&q=testing+engineer&tk=1cijretvbbatfa90";
        // $url = "http://whois.domaintools.com/sunilkumar.me";
        // $data = file_get_contents($url);

        // echo $data; 

        // preg_match('/<title>([^<]+)<\/title>/i', $data, $matches);
        // $title = $matches[1];

        // preg_match('/<img[^>]*src=[\'"]([^\'"]+)[\'"][^>]*>/i', $data, $matches);
        // $img = $matches[1];

        // echo $title."<br>\n";
        // echo $img;

        // Schema::table('apps', function (Blueprint $table) {
        //     $table->longText('roles');
        // });
    }
}