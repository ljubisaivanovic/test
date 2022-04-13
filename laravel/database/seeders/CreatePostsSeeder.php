<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreatePostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            [
                'topic' => 'Soccer',
                'created_at' => date('Y-m-d H:i:s', time()),
            ],
            [
                'topic' => 'Basketball',
                'created_at' => date('Y-m-d H:i:s', time()),
            ],
            [
                'topic' => 'Tennis',
                'created_at' => date('Y-m-d H:i:s', time()),
            ],
            [
                'topic' => 'Volleyball',
                'created_at' => date('Y-m-d H:i:s', time()),
            ],
            [
                'topic' => 'Baseball',
                'created_at' => date('Y-m-d H:i:s', time()),
            ],
        ]);
    }
}
