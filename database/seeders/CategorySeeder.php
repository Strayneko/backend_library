<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Fantasy',
            'logo' => 'http://123123',
            'description' => 'Lorem ipsum description',
            'age_rating' => 17,
        ]);
    }
}
