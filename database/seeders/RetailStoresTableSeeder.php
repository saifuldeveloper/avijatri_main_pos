<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RetailStoresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('retail_stores')->delete();
        
        \DB::table('retail_stores')->insert(array (
            0 => 
            array (
                'id' => 1,
                'shop_name' => 'Tarek Rahaman',
                'address' => 'Gazipur',
                'mobile_no' => '019335452',
                'onetime_buyer' => 0,
                'created_at' => '2024-04-21 11:31:42',
                'updated_at' => '2024-04-21 11:31:42',
            ),
            1 => 
            array (
                'id' => 2,
                'shop_name' => 'Nur amin',
                'address' => 'savar',
                'mobile_no' => '3434343',
                'onetime_buyer' => 0,
                'created_at' => '2024-04-21 11:31:56',
                'updated_at' => '2024-04-21 11:31:56',
            ),
        ));
        
        
    }
}