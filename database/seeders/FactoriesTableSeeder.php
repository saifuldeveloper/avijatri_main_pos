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
                'name' => 'Al Amin',
                'address' => 'Thakurgaon',
                'mobile_no' => '014785554542',
                'created_at' => '2024-04-30 16:00:23',
                'updated_at' => '2024-04-30 16:00:23',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Bokkor',
                'address' => 'Mirpur',
                'mobile_no' => '45125545',
                'created_at' => '2024-04-30 16:00:40',
                'updated_at' => '2024-04-30 16:00:40',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Mamun',
                'address' => 'Rangpur',
                'mobile_no' => '06985185',
                'created_at' => '2024-04-30 16:01:00',
                'updated_at' => '2024-04-30 16:01:00',
            ),
        ));
        
        
    }
}