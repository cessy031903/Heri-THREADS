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
        Schema::create('attires', function (Blueprint $table) {
            $table->id();
            $table->string('name_general', 255);
            $table->string('name_dialect', 255);
            $table->enum('municipality', [
                'Alfonso Lista', 'Aguinaldo', 'Asipulo', 'Banaue', 'Hingyon',
                'Hungduan', 'Kiangan', 'Lagawe', 'Lamut', 'Mayoyao', 'Tinoc',
            ]);
            $table->enum('gender', ['women', 'men']);
            $table->text('description');
            $table->string('source_info', 500)->nullable();
            $table->string('image_path', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attires');
    }
};
