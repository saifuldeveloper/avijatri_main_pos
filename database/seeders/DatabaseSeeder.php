<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(OperatorSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(GiftTypesTableSeeder::class);
        $this->call(GiftsTableSeeder::class);
        $this->call(ColorsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(BankAccountsTableSeeder::class);
        $this->call(FactoriesTableSeeder::class);
        $this->call(RetailStoresTableSeeder::class);
        $this->call(GiftSuppliersTableSeeder::class);
        $this->call(AccountsTableSeeder::class);
        $this->call(AccountBooksTableSeeder::class);
    }
}
