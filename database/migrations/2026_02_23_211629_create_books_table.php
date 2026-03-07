<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');       // タイトル
            $table->string('author')->nullable(); // 著者（空でもOK）
            $table->string('isbn');        // ISBN
            $table->string('image_url')->nullable(); // 表紙画像のURL
            $table->text('description')->nullable(); // 本の説明
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
