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
        Schema::create('preparations', function (Blueprint $table) {
            $table->id();
            $table->string('item_name'); // 準備するものの名前
            $table->boolean('is_completed')->default(false); // チェックボックスの状態
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preparations');
    }
};
