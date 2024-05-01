<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GiftSuppliersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('gift_suppliers')->delete();
        
        \DB::table('gift_suppliers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Nasir uddin',
                'address' => 'Kumilla',
                'mobile_no' => '056466',
                'created_at' => '2024-04-30 16:02:42',
                'updated_at' => '2024-04-30 16:02:42',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Belal sarkar',
                'address' => 'Jattrabari',
                'mobile_no' => '0524652',
                'created_at' => '2024-04-30 16:03:05',
                'updated_at' => '2024-04-30 16:03:05',
            ),
        ));
        
        
    }
}