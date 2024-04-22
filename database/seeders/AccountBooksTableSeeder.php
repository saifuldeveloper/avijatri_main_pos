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
                'account_id' => 1,
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
                'created_at' => '2024-04-22 10:45:39',
                'updated_at' => '2024-04-22 10:45:39',
            ),
            1 => 
            array (
                'id' => 2,
                'account_id' => 2,
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
                'created_at' => '2024-04-22 10:45:56',
                'updated_at' => '2024-04-22 10:45:56',
            ),
            2 => 
            array (
                'id' => 3,
                'account_id' => 3,
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
                'created_at' => '2024-04-22 10:46:10',
                'updated_at' => '2024-04-22 10:46:10',
            ),
            3 => 
            array (
                'id' => 4,
                'account_id' => 4,
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
                'created_at' => '2024-04-22 10:46:26',
                'updated_at' => '2024-04-22 10:46:26',
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
                'created_at' => '2024-04-22 10:46:48',
                'updated_at' => '2024-04-22 10:46:48',
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
                'created_at' => '2024-04-22 10:47:01',
                'updated_at' => '2024-04-22 10:47:01',
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
                'created_at' => '2024-04-22 10:47:34',
                'updated_at' => '2024-04-22 10:47:34',
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
                'created_at' => '2024-04-22 10:47:58',
                'updated_at' => '2024-04-22 10:47:58',
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
                'created_at' => '2024-04-22 10:48:19',
                'updated_at' => '2024-04-22 10:48:19',
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
                'created_at' => '2024-04-22 10:48:40',
                'updated_at' => '2024-04-22 10:48:40',
            ),
        ));
        
        
    }
}