<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountBooksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('account_books')->delete();
        
        \DB::table('account_books')->insert(array (
            0 => 
            array (
                'id' => 1,
                'account_id' => 5,
                'account_type' => 'bank-account',
                'previous_balance' => 0.0,
                'open' => 1,
                'commission' => 0.0,
                'staff' => 0.0,
                'discount' => 0.0,
                'due' => 0.0,
                'deadline' => NULL,
                'balance_carry_forward' => NULL,
                'closing_balance' => 0.0,
                'closing_date' => NULL,
                'created_at' => '2024-04-20 17:50:05',
                'updated_at' => '2024-04-20 17:50:05',
            ),
            1 => 
            array (
                'id' => 2,
                'account_id' => 6,
                'account_type' => 'bank-account',
                'previous_balance' => 0.0,
                'open' => 1,
                'commission' => 0.0,
                'staff' => 0.0,
                'discount' => 0.0,
                'due' => 0.0,
                'deadline' => NULL,
                'balance_carry_forward' => NULL,
                'closing_balance' => 0.0,
                'closing_date' => NULL,
                'created_at' => '2024-04-20 17:50:23',
                'updated_at' => '2024-04-20 17:50:23',
            ),
            2 => 
            array (
                'id' => 3,
                'account_id' => 7,
                'account_type' => 'bank-account',
                'previous_balance' => 0.0,
                'open' => 1,
                'commission' => 0.0,
                'staff' => 0.0,
                'discount' => 0.0,
                'due' => 0.0,
                'deadline' => NULL,
                'balance_carry_forward' => NULL,
                'closing_balance' => 0.0,
                'closing_date' => NULL,
                'created_at' => '2024-04-20 17:50:35',
                'updated_at' => '2024-04-20 17:50:35',
            ),
            3 => 
            array (
                'id' => 4,
                'account_id' => 8,
                'account_type' => 'bank-account',
                'previous_balance' => 0.0,
                'open' => 1,
                'commission' => 0.0,
                'staff' => 0.0,
                'discount' => 0.0,
                'due' => 0.0,
                'deadline' => NULL,
                'balance_carry_forward' => NULL,
                'closing_balance' => 0.0,
                'closing_date' => NULL,
                'created_at' => '2024-04-20 17:50:59',
                'updated_at' => '2024-04-20 17:50:59',
            ),
            4 => 
            array (
                'id' => 5,
                'account_id' => 1,
                'account_type' => 'factory',
                'previous_balance' => 0.0,
                'open' => 1,
                'commission' => 0.0,
                'staff' => 0.0,
                'discount' => 0.0,
                'due' => 0.0,
                'deadline' => NULL,
                'balance_carry_forward' => NULL,
                'closing_balance' => 0.0,
                'closing_date' => NULL,
                'created_at' => '2024-04-20 17:51:44',
                'updated_at' => '2024-04-20 17:51:44',
            ),
            5 => 
            array (
                'id' => 6,
                'account_id' => 2,
                'account_type' => 'factory',
                'previous_balance' => 0.0,
                'open' => 1,
                'commission' => 0.0,
                'staff' => 0.0,
                'discount' => 0.0,
                'due' => 0.0,
                'deadline' => NULL,
                'balance_carry_forward' => NULL,
                'closing_balance' => 0.0,
                'closing_date' => NULL,
                'created_at' => '2024-04-20 17:52:12',
                'updated_at' => '2024-04-20 17:52:12',
            ),
            6 => 
            array (
                'id' => 7,
                'account_id' => 3,
                'account_type' => 'factory',
                'previous_balance' => 0.0,
                'open' => 1,
                'commission' => 0.0,
                'staff' => 0.0,
                'discount' => 0.0,
                'due' => 0.0,
                'deadline' => NULL,
                'balance_carry_forward' => NULL,
                'closing_balance' => 0.0,
                'closing_date' => NULL,
                'created_at' => '2024-04-20 17:52:43',
                'updated_at' => '2024-04-20 17:52:43',
            ),
            7 => 
            array (
                'id' => 8,
                'account_id' => 1,
                'account_type' => 'retail-store',
                'previous_balance' => 0.0,
                'open' => 1,
                'commission' => 0.0,
                'staff' => 0.0,
                'discount' => 0.0,
                'due' => 0.0,
                'deadline' => NULL,
                'balance_carry_forward' => NULL,
                'closing_balance' => 0.0,
                'closing_date' => NULL,
                'created_at' => '2024-04-20 17:53:35',
                'updated_at' => '2024-04-20 17:53:35',
            ),
            8 => 
            array (
                'id' => 9,
                'account_id' => 2,
                'account_type' => 'retail-store',
                'previous_balance' => 0.0,
                'open' => 1,
                'commission' => 0.0,
                'staff' => 0.0,
                'discount' => 0.0,
                'due' => 0.0,
                'deadline' => NULL,
                'balance_carry_forward' => NULL,
                'closing_balance' => 0.0,
                'closing_date' => NULL,
                'created_at' => '2024-04-20 17:53:56',
                'updated_at' => '2024-04-20 17:53:56',
            ),
            9 => 
            array (
                'id' => 10,
                'account_id' => 3,
                'account_type' => 'retail-store',
                'previous_balance' => 0.0,
                'open' => 1,
                'commission' => 0.0,
                'staff' => 0.0,
                'discount' => 0.0,
                'due' => 0.0,
                'deadline' => NULL,
                'balance_carry_forward' => NULL,
                'closing_balance' => 0.0,
                'closing_date' => NULL,
                'created_at' => '2024-04-20 17:54:33',
                'updated_at' => '2024-04-20 17:54:33',
            ),
        ));
        
        
    }
}