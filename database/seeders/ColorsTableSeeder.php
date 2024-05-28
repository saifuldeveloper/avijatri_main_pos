<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ColorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('colors')->delete();

        \DB::table('colors')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'কালো',
                'created_at' => '2024-02-21 10:03:36',
                'updated_at' => '2024-02-21 10:03:36',
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'নীল',
                'created_at' => '2024-02-21 10:04:07',
                'updated_at' => '2024-02-21 10:04:07',
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'মেরুন',
                'created_at' => '2024-02-21 10:04:15',
                'updated_at' => '2024-02-21 10:04:15',
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'মাস্টার',
                'created_at' => '2024-02-21 10:04:22',
                'updated_at' => '2024-02-21 10:04:22',
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'আকাশী',
                'created_at' => '2024-02-21 10:04:28',
                'updated_at' => '2024-02-21 10:04:28',
            ),
        ));
    }
}
