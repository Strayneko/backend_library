<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::create([
            'name' => 'Fulan Kurniawan',
            'birth_date' => '01-01-2000',
            'gender' => 1,
            'photo' => 'http://123123',
            'bio' => 'Lorem ipsum bio',
            'address' => 'Jl. Fulan membara No 12',
            'phone_number' => '1234567891234',
            'email' => 'fulan@gmail.com',
        ]);
    }
}
