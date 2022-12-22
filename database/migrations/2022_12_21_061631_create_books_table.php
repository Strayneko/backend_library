<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->integer('author_id');
            $table->string('title', 255);
            $table->date('published_at');
            $table->integer('total_pages');
            $table->text('description');
            $table->text('image');
            $table->string('book_language', 20);
            $table->string('isbn', 13)->unique();
            $table->string('publisher', 100);
            $table->string('type', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
