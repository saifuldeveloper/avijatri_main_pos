<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accounts')->delete();
        
        \DB::table('accounts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type' => 'bank-account',
                'name' => 'cash',
                'created_at' => '2024-04-30 15:57:12',
                'updated_at' => '2024-04-30 15:57:12',
            ),
            1 => 
            array (
                'id' => 2,
                'type' => 'bank-account',
                'name' => 'Pubali Bank',
                'created_at' => '2024-04-30 15:58:08',
                'updated_at' => '2024-04-30 15:58:08',
            ),
            2 => 
            array (
                'id' => 3,
                'type' => 'bank-account',
                'name' => 'জনতা ব্যাংক',
                'created_at' => '2024-04-30 15:58:31',
                'updated_at' => '2024-04-30 15:58:31',
            ),
            3 => 
            array (
                'id' => 4,
                'type' => 'bank-account',
                'name' => 'জনতা ব্যাংক',
                'created_at' => '2024-04-30 15:59:26',
                'updated_at' => '2024-04-30 15:59:26',
            ),
            4 => 
            array (
                'id' => 5,
                'type' => 'bank-account',
                'name' => 'Dutch Bangla Bank',
                'created_at' => '2024-04-30 15:59:45',
                'updated_at' => '2024-04-30 15:59:45',
            ),
            5 => 
            array (
                'id' => 6,
                'type' => 'bank-account',
                'name' => 'City Bank',
                'created_at' => '2024-04-30 15:59:56',
                'updated_at' => '2024-04-30 15:59:56',
            ),
            6 => 
            array (
                'id' => 1,
                'type' => 'factory',
                'name' => 'Al Amin',
                'created_at' => '2024-04-30 16:00:23',
                'updated_at' => '2024-04-30 16:00:23',
            ),
            7 => 
            array (
                'id' => 2,
                'type' => 'factory',
                'name' => 'Bokkor',
                'created_at' => '2024-04-30 16:00:40',
                'updated_at' => '2024-04-30 16:00:40',
            ),
            8 => 
            array (
                'id' => 3,
                'type' => 'factory',
                'name' => 'Mamun',
                'created_at' => '2024-04-30 16:01:00',
                'updated_at' => '2024-04-30 16:01:00',
            ),
            9 => 
            array (
                'id' => 1,
                'type' => 'retail-store',
                'name' => 'Tarek Rahaman',
                'created_at' => '2024-04-30 16:01:29',
                'updated_at' => '2024-04-30 16:01:29',
            ),
            10 => 
            array (
                'id' => 2,
                'type' => 'retail-store',
                'name' => 'Ariful',
                'created_at' => '2024-04-30 16:01:48',
                'updated_at' => '2024-04-30 16:01:48',
            ),
            11 => 
            array (
                'id' => 3,
                'type' => 'retail-store',
                'name' => 'Rasel sarkar',
                'created_at' => '2024-04-30 16:02:06',
                'updated_at' => '2024-04-30 16:02:06',
            ),
            12 => 
            array (
                'id' => 1,
                'type' => 'gift-supplier',
                'name' => 'Nasir uddin',
                'created_at' => '2024-04-30 16:02:42',
                'updated_at' => '2024-04-30 16:02:42',
            ),
            13 => 
            array (
                'id' => 2,
                'type' => 'gift-supplier',
                'name' => 'Belal sarkar',
                'created_at' => '2024-04-30 16:03:05',
                'updated_at' => '2024-04-30 16:03:05',
            ),
        ));
        
        
    }
}