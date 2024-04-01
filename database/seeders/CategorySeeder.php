<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        if(Category::count() == 0){
            Category::insert([
                ['name' => 'সু', 'created_at' => $now, 'updated_at' => $now],
        		['name' => 'জে', 'created_at' => $now, 'updated_at' => $now],
        		['name' => 'লে', 'created_at' => $now, 'updated_at' => $now],
        		['name' => 'বেবি', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
    }
}
