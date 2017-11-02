<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles
        DB::table('roles')->insert([
            'slug' => 'user',
            'name' => 'User'
        ]);

        DB::table('roles')->insert([
            'slug' => 'admin',
            'name' => 'Admin'
        ]);
    }
}
