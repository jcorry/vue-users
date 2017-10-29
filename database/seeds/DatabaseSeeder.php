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
        $users = factory(App\Models\User::class, 10)->create()->each(
            function ($u) {
                $user = \Sentinel::findById($u->id);
                \Activation::create($user);
            }
        );

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
