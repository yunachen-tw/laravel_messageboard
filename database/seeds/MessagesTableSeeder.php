<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $board_id = DB::table('boards')->pluck('board_id');
        $user_id = DB::table('users')->pluck('user_id');

        DB::table('messages')->insert([
            'user_id' => $faker->randomElement($user_id),
            'board_id' => $faker->randomElement($board_id),
            'content' => str_random(50),
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ]);
    }
}
