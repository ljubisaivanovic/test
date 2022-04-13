<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateCommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->generateComments();

        DB::table('comments')->insert($data);
    }

    private function generateComments()
    {
        $string = 'Cool,Strange,Funny,Laughing,Nice,Awesome,Great,Horrible,Beautiful,PHP,Vegeta,Italy,Joost';
        $array = explode(',', strtolower($string));

        $results = [[]];

        foreach ($array as $element) {
            foreach ($results as $combination) {
                $results[] = array_merge([$element], $combination);
            }
        }

        $results = array_map(function ($element) {
            sort($element);

            return [
                'post_id' => rand(1, 5),
                'content' => implode(' ', $element),
                'abbreviation' => implode('', array_map(function ($word) {
                    return $word[0];
                }, $element)),
                'created_at' => date('Y-m-d H:i:s', time()),

            ];
        }, $results);

        array_shift($results);

        return $results;
    }
}
