<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ScoresTableSeeder extends Seeder
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
        $message_id = DB::table('messages')->pluck('message_id');

        $array = [-1, 0, 1];
        DB::table('scores')->insert([
            'user_id' => $faker->randomElement($user_id),
            'message_id' => $faker->randomElement($message_id),
            'score' => array_random($array),
            'created_at' => new DateTime,
            'updated_at' => new DateTime
        ]);
    }
}
