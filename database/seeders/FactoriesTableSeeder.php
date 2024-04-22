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
                'name' => 'Al Mamun',
                'address' => 'Thakurgaon',
                'mobile_no' => '01789639696',
                'created_at' => '2024-04-22 10:46:48',
                'updated_at' => '2024-04-22 10:46:48',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Bokkor',
                'address' => 'Rnagpur',
                'mobile_no' => '0241966325',
                'created_at' => '2024-04-22 10:47:01',
                'updated_at' => '2024-04-22 10:47:01',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Al  Amin',
                'address' => 'Pirgonj',
                'mobile_no' => '018639852',
                'created_at' => '2024-04-22 10:47:34',
                'updated_at' => '2024-04-22 10:47:34',
            ),
        ));
        
        
    }
}