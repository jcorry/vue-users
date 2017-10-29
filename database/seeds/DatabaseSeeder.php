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
        DB::table('users')->insert([
            'email' => 'foo@bar.com',
            'password' => bcrypt('password'),
            'first_name' => 'foo',
            'last_name' => 'bar',
            'phone' => '4045551212',
        ]);
        
        // $this->call(UsersTableSeeder::class);
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
