<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::create(['name' => 'অপারেটর', 'email' => 'aaa@bbb.com', 'password' => bcrypt('abcd1234')]);
        $user->assignRole('operator');
    }
}
