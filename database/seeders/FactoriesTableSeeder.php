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
                'name' => 'Mamun islam',
                'address' => 'Thakurgaon',
                'mobile_no' => '01586296296',
                'created_at' => '2024-04-21 11:30:01',
                'updated_at' => '2024-04-21 11:30:01',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'AL amin',
                'address' => 'Rangpur',
                'mobile_no' => '01896635458',
                'created_at' => '2024-04-21 11:30:17',
                'updated_at' => '2024-04-21 11:30:17',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Ruhul',
                'address' => 'Bogura',
                'mobile_no' => '0179634565',
                'created_at' => '2024-04-21 11:30:49',
                'updated_at' => '2024-04-21 11:30:49',
            ),
        ));
        
        
    }
}