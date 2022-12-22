<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::create([
            'category_id' => 1,
            'author_id' => 1,
            'title' => 'Kage No Jitsuryoukusha',
            'published_at' => '1-1-2000',
            'total_pages' => 10,
            'description' => 'Lorem ipsum description',
            'image' => 'https://upload.wikimedia.org/wikipedia/id/2/2c/The_Eminence_in_Shadow_light_novel_volume_1_cover.jpg',
            'book_language' => 'Japanese',
            'isbn' => '1234567891321',
            'publisher' => 'A publisher',
            'type' => 'Light Novel',
        ]);
    }
}
