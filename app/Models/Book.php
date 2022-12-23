<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // trim and titlecase string
    private static function trimAndTitle($book): void
    {
        // trim description
        $book->description = Str::of($book->description)->trim()->ucfirst();

        // trim and title case book title, type, publisher, and language
        $book->title = Str::of($book->title)->trim()->title();
        $book->type = Str::of($book->type)->trim()->title();
        $book->publisher = Str::of($book->publisher)->trim()->title();
        $book->book_language = Str::of($book->book_language)->trim()->title();
    }
    public static function boot()
    {
        parent::boot();
        static::creating(fn (Book $book) => static::trimAndTitle($book));
        static::updating(fn (Book $book) => static::trimAndTitle($book));
    }
}
