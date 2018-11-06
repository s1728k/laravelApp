<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(App\Models\Post::class, 2)->create()->each(function($u) {
		    $u->posts()->save(factory(App\Models\Post::class)->make());
		});
    }
}
