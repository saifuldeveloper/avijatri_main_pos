<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GiftTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('gift_types')->delete();
        
        \DB::table('gift_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'বক্স',
                'created_at' => '2024-02-20 15:43:24',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'ব্যাগ',
                'created_at' => '2024-02-20 15:43:28',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'গিফট',
                'created_at' => '2024-02-20 15:43:30',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}