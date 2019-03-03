<?php

namespace App\Traits;

use App\App;

trait ExportsDb
{
    public function exportDb($app_id = null, $db_name = null, $con = null)
    {
        $app_id = $app_id??$this->app_id;
        $app = App::findOrfail($app_id);
        if($app->user_id != \Auth::user()->id){
        	return ['status' => 'un-authorized'];
        }
        $database = $db_name??$app->name;
        $database = str_replace(' ','_',$database);

        if (! $database) {
            return ['status' => 'failed'];
        }

  //       $link = mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'));
		// if (!$link) {
		//     die('Could not connect: ' . mysqli_connect_error());
		// }

		// $sql = [sprintf('CREATE DATABASE %s;', $database)];
		// array_push($sql, sprintf('CREATE TABLE `$s.app10_users` SELECT * FROM `app10_users`;', $database));
		// $sql = array(
		//     'DROP TABLE IF EXISTS `backup_db.backup_table`;',
		//     'CREATE TABLE `backup_db.backup_table` SELECT * FROM `live_db.live_table`'
		// );

		// $sql = sprintf('CREATE DATABASE %s;', $database);
		// $sql = sprintf('CREATE TABLE `tta.app10_users` SELECT * FROM `apps_db.app10_users`;', $database);

		// $db_selected = mysqli_select_db($link, $database);

		// if (!$db_selected) {
			// $db_selected = mysqli_select_db($link, 'apps_db');
			// foreach ($sql as $query) {
		 //        if (!$link->query($query)) {
		 //            return ['status' => $link->error];
		 //        }
		 //    }
		 //  if (mysqli_query($link, $sql)) {
		 //    return ['status' => 'success'];
		 //  } else {
			// return ['status' => mysqli_error($link)];
		 //  }
		// }

		// mysqli_close($link);
		// $sql = 'mysql apps_db -u root -p Krishna@1 -e '."'".'show tables like "app10_%"'."'".'| grep -v Tables_in | xargs mysqldump apps_db -u root -p Krishna@1 > '.base_path().'/asdf.sql';
		// $sql = 'mysqldump -u root -p nara apps_db.app10_users > '.base_path().'\asdf.sql';
		// \Log::Info($sql);
		// exec($sql);
		$DBUSER=env('DB_USERNAME');
		$DBPASSWD=env('DB_PASSWORD');
		$DATABASE=$con??"apps_db";
		$TABLES=implode(' ', $this->getRawTables($app_id));

		$filename = "backup-".$database .'-'. date("d-m-Y") . ".sql";
		// $mime = "application/x-gzip";

		// header( "Content-Type: " . $mime );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

		$cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE $TABLES";   // | gzip --best

		passthru( $cmd );

		exit(0);
    }

    /**
     * @param  string $host
     * @param  integer $port
     * @param  string $username
     * @param  string $password
     * @return PDO
     */
    private function getPDOConnection($host, $db, $username, $password)
    {
        return new PDO(sprintf('mysql:host=%s;db=%d;', $host, $db), $username, $password);
    }
}