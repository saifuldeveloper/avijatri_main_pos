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
                'account_no' => '-',
                'bank' => 'ক্যাশ',
                'branch' => 'cash',
                'created_at' => '2024-04-21 11:27:55',
                'updated_at' => '2024-04-21 11:27:55',
            ),
            1 => 
            array (
                'id' => 2,
                'account_no' => '2917',
                'bank' => 'Pubali Bank',
                'branch' => 'B.B. Avenue',
                'created_at' => '2024-04-21 11:28:09',
                'updated_at' => '2024-04-21 11:28:09',
            ),
            2 => 
            array (
                'id' => 3,
                'account_no' => '১২২৩',
                'bank' => 'জনতা ব্যাংক',
                'branch' => 'গুলিস্তান',
                'created_at' => '2024-04-21 11:28:23',
                'updated_at' => '2024-04-21 11:28:23',
            ),
            3 => 
            array (
                'id' => 4,
                'account_no' => '51489962',
                'bank' => 'Dutch Bangla Bank',
                'branch' => 'Dhaka',
                'created_at' => '2024-04-21 11:28:38',
                'updated_at' => '2024-04-21 11:28:38',
            ),
        ));
        
        
    }
}