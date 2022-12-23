<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Author extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // trim and titlecase string
    private static function trimAndTitle($author): void
    {
        $author->name = Str::of($author->name)->trim()->title();
        $author->bio = Str::of($author->bio)->trim()->ucfirst();
        $author->address = Str::of($author->address)->trim()->ucfirst();
        // lowercasing email
        $author->email = Str::of($author->email)->trim()->lower();
    }
    public static function boot()
    {
        parent::boot();

        static::creating(fn (Author $author) => static::trimAndTitle($author));
        static::updating(fn (Author $author) => static::trimAndTitle($author));
    }
}
