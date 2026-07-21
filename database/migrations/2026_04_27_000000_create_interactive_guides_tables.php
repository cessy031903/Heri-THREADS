<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interactive_guides', function (Blueprint $table) {
            $table->id();
            $table->enum('municipality', [
                'Alfonso Lista', 'Aguinaldo', 'Asipulo', 'Banaue', 'Hingyon',
                'Hungduan', 'Kiangan', 'Lagawe', 'Lamut', 'Mayoyao', 'Tinoc',
            ])->unique();
            $table->string('title', 255);
            $table->string('instruction', 255)->nullable();
            $table->string('image_path', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('guide_hotspots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interactive_guide_id')
                ->constrained('interactive_guides')
                ->cascadeOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->string('label', 255);
            $table->text('description')->nullable();
            // Percentage positions (0-100) relative to the guide image.
            $table->decimal('pos_x', 5, 2)->default(50);
            $table->decimal('pos_y', 5, 2)->default(50);
            $table->foreignId('attire_id')
                ->nullable()
                ->constrained('attires')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guide_hotspots');
        Schema::dropIfExists('interactive_guides');
    }
};
