<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('categories')->delete();

        \DB::table('categories')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'সু',
                'parent_id' => 0,
                'created_at' => '2024-02-22 06:05:27',
                'updated_at' => '2024-02-22 06:05:27',

            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'জে',
                'parent_id' => 0,
                'created_at' => '2024-02-22 06:05:27',
                'updated_at' => '2024-02-22 06:05:27',

            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'লে',
                'parent_id' => 0,
                'created_at' => '2024-02-22 06:05:27',
                'updated_at' => '2024-02-22 06:05:27',

            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'বেবি',
                'parent_id' => 0,
                'created_at' => '2024-02-22 06:05:27',
                'updated_at' => '2024-02-22 06:05:27',
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'আংটা',
                'parent_id' => 2,
                'created_at' => '2024-02-22 06:08:27',
                'updated_at' => '2024-02-22 06:08:27',

            ),
            5 =>
            array(
                'id' => 6,
                'name' => 'লোফার',
                'parent_id' => 2,
                'created_at' => '2024-02-22 06:08:43',
                'updated_at' => '2024-02-22 06:08:43',
            ),
            6 =>
            array(
                'id' => 7,
                'name' => 'সীট',
                'parent_id' => 2,
                'created_at' => '2024-02-22 06:09:02',
                'updated_at' => '2024-02-22 06:09:02',
            ),
            7 =>
            array(
                'id' => 8,
                'name' => 'কলাপুরি',
                'parent_id' => 2,
                'created_at' => '2024-02-22 06:09:34',
                'updated_at' => '2024-02-22 06:09:34',
            ),
            8 =>
            array(
                'id' => 9,
                'name' => 'গোলাই',
                'parent_id' => 3,
                'created_at' => '2024-02-22 06:09:48',
                'updated_at' => '2024-02-22 06:09:48',
            ),
            9 =>
            array(
                'id' => 10,
                'name' => 'কারচুপী',
                'parent_id' => 3,
                'created_at' => '2024-02-22 06:10:02',
                'updated_at' => '2024-02-22 06:10:02',
            ),
            10 =>
            array(
                'id' => 11,
                'name' => 'সীট পাম্পী',
                'parent_id' => 3,
                'created_at' => '2024-02-22 06:10:18',
                'updated_at' => '2024-02-22 06:10:18',
            ),
            11 =>
            array(
                'id' => 12,
                'name' => 'পাম',
                'parent_id' => 1,
                'created_at' => '2024-02-22 06:10:40',
                'updated_at' => '2024-02-22 06:10:40',
            ),
            12 =>
            array(
                'id' => 13,
                'name' => 'টেপ',
                'parent_id' => 1,
                'created_at' => '2024-02-22 06:10:52',
                'updated_at' => '2024-02-22 06:10:52',
            ),
            13 =>
            array(
                'id' => 15,
                'name' => '০-২',
                'parent_id' => 4,
                'created_at' => '2024-02-22 06:11:19',
                'updated_at' => '2024-02-22 06:11:19',
            ),
        ));
    }
}
