<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GiftsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('gifts')->delete();
        
        \DB::table('gifts')->insert(array (
            0 => 
            array (
                'id' => 5,
                'name' => 'জে',
                'gift_type_id' => '1',
                'initial_count' => 0,
                'created_at' => '2024-02-20 10:02:42',
                'updated_at' => '2024-02-20 10:02:42',
            ),
            1 => 
            array (
                'id' => 6,
                'name' => 'জে-ব্যাগ',
                'gift_type_id' => '2',
                'initial_count' => 0,
                'created_at' => '2024-02-20 10:02:55',
                'updated_at' => '2024-02-20 10:02:55',
            ),
            2 => 
            array (
                'id' => 7,
                'name' => 'চাবির রিং',
                'gift_type_id' => '3',
                'initial_count' => 0,
                'created_at' => '2024-02-20 10:03:07',
                'updated_at' => '2024-02-20 10:03:07',
            ),
            3 => 
            array (
                'id' => 8,
                'name' => 'জ্যাকেট',
                'gift_type_id' => '3',
                'initial_count' => 0,
                'created_at' => '2024-02-20 10:03:20',
                'updated_at' => '2024-02-20 10:03:20',
            ),
            4 => 
            array (
                'id' => 9,
                'name' => 'জেঃ',
                'gift_type_id' => '1',
                'initial_count' => 0,
                'created_at' => '2024-02-20 10:03:30',
                'updated_at' => '2024-02-20 10:03:30',
            ),
            5 => 
            array (
                'id' => 10,
                'name' => 'লে-বক্স',
                'gift_type_id' => '1',
                'initial_count' => 0,
                'created_at' => '2024-02-20 10:03:54',
                'updated_at' => '2024-02-20 10:03:54',
            ),
            6 => 
            array (
                'id' => 11,
                'name' => 'জ্যাকেট',
                'gift_type_id' => '3',
                'initial_count' => 0,
                'created_at' => '2024-02-20 10:04:08',
                'updated_at' => '2024-02-20 10:04:08',
            ),
            7 => 
            array (
                'id' => 12,
                'name' => 'শূ',
                'gift_type_id' => '1',
                'initial_count' => 0,
                'created_at' => '2024-03-03 04:53:21',
                'updated_at' => '2024-03-03 04:53:21',
            ),
            8 => 
            array (
                'id' => 13,
                'name' => 'সিট',
                'gift_type_id' => '1',
                'initial_count' => 0,
                'created_at' => '2024-03-03 04:54:02',
                'updated_at' => '2024-03-03 04:54:02',
            ),
            9 => 
            array (
                'id' => 14,
                'name' => 'লে ব্যাগ',
                'gift_type_id' => '2',
                'initial_count' => 0,
                'created_at' => '2024-03-03 04:55:14',
                'updated_at' => '2024-03-03 04:55:14',
            ),
        ));
        
        
    }
}