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
                'address' => 'Dhaka',
                'mobile_no' => '05969298',
                'onetime_buyer' => 0,
                'created_at' => '2024-04-30 16:01:29',
                'updated_at' => '2024-04-30 16:01:29',
            ),
            1 => 
            array (
                'id' => 2,
                'shop_name' => 'Ariful',
                'address' => 'barisal',
                'mobile_no' => '054798522',
                'onetime_buyer' => 0,
                'created_at' => '2024-04-30 16:01:48',
                'updated_at' => '2024-04-30 16:01:48',
            ),
            2 => 
            array (
                'id' => 3,
                'shop_name' => 'Rasel sarkar',
                'address' => 'Mohammodpur',
                'mobile_no' => '014586556',
                'onetime_buyer' => 0,
                'created_at' => '2024-04-30 16:02:06',
                'updated_at' => '2024-04-30 16:02:06',
            ),
        ));
        
        
    }
}