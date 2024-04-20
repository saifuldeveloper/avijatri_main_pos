<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FactoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('factories')->delete();
        
        \DB::table('factories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Md Rasel Sarkar',
                'address' => 'Pabna',
                'mobile_no' => '0179865655',
                'created_at' => '2024-04-20 17:51:44',
                'updated_at' => '2024-04-20 17:51:44',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Md Sakil Sarkar',
                'address' => 'Mirpur 10',
                'mobile_no' => '01945639634',
                'created_at' => '2024-04-20 17:52:12',
                'updated_at' => '2024-04-20 17:52:12',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Ariful sarkar',
                'address' => 'Barisal',
                'mobile_no' => '01485939685',
                'created_at' => '2024-04-20 17:52:43',
                'updated_at' => '2024-04-20 17:52:43',
            ),
        ));
        
        
    }
}