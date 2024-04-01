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
                'bank' => 'cash',
                'branch' => 'ক্যাশ',
                'created_at' => '2024-02-21 05:48:29',
                'updated_at' => '2024-02-21 05:48:54',
            ),
            1 => 
            array (
                'id' => 2,
                'account_no' => 'B.B. Avenue',
                'bank' => '2917',
                'branch' => 'Pubali Bank',
                'created_at' => '2024-02-21 05:49:14',
                'updated_at' => '2024-02-21 05:49:14',
            ),
            2 => 
            array (
                'id' => 3,
                'account_no' => 'গুলিস্তান',
                'bank' => '১২২৩',
                'branch' => 'জনতা ব্যাংক',
                'created_at' => '2024-02-21 05:49:35',
                'updated_at' => '2024-02-21 05:49:35',
            ),
            3 => 
            array (
                'id' => 4,
                'account_no' => 'B.B Avenue',
                'bank' => '51489962',
                'branch' => 'Dutch Bangla Bank',
                'created_at' => '2024-02-21 05:50:04',
                'updated_at' => '2024-02-21 05:50:04',
            ),
        ));
        
        
    }
}