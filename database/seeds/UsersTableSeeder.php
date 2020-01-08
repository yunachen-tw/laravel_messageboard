<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'account' => str_random(10),
            'password' => bcrypt('secret'),
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ]);
    }
}
