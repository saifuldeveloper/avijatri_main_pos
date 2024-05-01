<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BankAccountsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bank_accounts')->delete();
        
        \DB::table('bank_accounts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'account_no' => 'cash',
                'bank' => 'cash',
                'branch' => '-',
                'created_at' => '2024-04-30 15:57:12',
                'updated_at' => '2024-04-30 15:57:33',
            ),
            1 => 
            array (
                'id' => 2,
                'account_no' => '2917',
                'bank' => 'Pubali Bank',
                'branch' => 'B.B. Avenue',
                'created_at' => '2024-04-30 15:58:08',
                'updated_at' => '2024-04-30 15:58:08',
            ),
            2 => 
            array (
                'id' => 3,
                'account_no' => '১২২৩',
                'bank' => 'জনতা ব্যাংক',
                'branch' => 'গুলিস্তান',
                'created_at' => '2024-04-30 15:58:31',
                'updated_at' => '2024-04-30 15:58:31',
            ),
            3 => 
            array (
                'id' => 4,
                'account_no' => '১২২৩',
                'bank' => 'জনতা ব্যাংক',
                'branch' => 'গুলিস্তান',
                'created_at' => '2024-04-30 15:59:26',
                'updated_at' => '2024-04-30 15:59:26',
            ),
            4 => 
            array (
                'id' => 5,
                'account_no' => '51489962',
                'bank' => 'Dutch Bangla Bank',
                'branch' => 'Dhaka',
                'created_at' => '2024-04-30 15:59:45',
                'updated_at' => '2024-04-30 15:59:45',
            ),
            5 => 
            array (
                'id' => 6,
                'account_no' => '343434',
                'bank' => 'City Bank',
                'branch' => 'uttora',
                'created_at' => '2024-04-30 15:59:56',
                'updated_at' => '2024-04-30 15:59:56',
            ),
        ));
        
        
    }
}