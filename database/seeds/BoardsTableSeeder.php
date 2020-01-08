<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BoardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $user_id = DB::table('users')->pluck('user_id');
		
        DB::table('boards')->insert([
            'user_id' => $faker->randomElement($user_id),
            'title' => str_random(10),
            'describe' => str_random(10),
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ]);
    }
}
