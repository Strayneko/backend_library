<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    // trim and titlecase string
    private static function trimAndTitle($category): void
    {
        $category->name = Str::of($category->name)->trim()->title();
        $category->description = Str::of($category->description)->trim()->ucfirst();
    }
    public static function boot()
    {
        parent::boot();

        static::creating(fn (Category $category) => static::trimAndTitle($category));
        static::updating(fn (Category $category) => static::trimAndTitle($category));
    }
}
