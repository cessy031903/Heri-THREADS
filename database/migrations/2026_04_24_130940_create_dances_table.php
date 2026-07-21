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
        Schema::create('dances', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->enum('category', ['pagaddut', 'hinggatut', 'dinuy-a']);
            $table->text('description');
            $table->string('video_url', 255);
            $table->string('image_path', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dances');
    }
};
