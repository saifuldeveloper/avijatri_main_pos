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
                'shop_name' => 'Tarek Rahakman',
                'address' => 'DInajpur',
                'mobile_no' => '0178996269',
                'onetime_buyer' => 0,
                'created_at' => '2024-04-22 10:47:58',
                'updated_at' => '2024-04-22 10:47:58',
            ),
            1 => 
            array (
                'id' => 2,
                'shop_name' => 'Prevel sarkar',
                'address' => 'chiriribandar',
                'mobile_no' => '0148939698',
                'onetime_buyer' => 0,
                'created_at' => '2024-04-22 10:48:19',
                'updated_at' => '2024-04-22 10:48:19',
            ),
            2 => 
            array (
                'id' => 3,
                'shop_name' => 'Masum ali',
                'address' => 'Mohammadpur',
                'mobile_no' => '01983069859',
                'onetime_buyer' => 0,
                'created_at' => '2024-04-22 10:48:40',
                'updated_at' => '2024-04-22 10:48:40',
            ),
        ));
        
        
    }
}