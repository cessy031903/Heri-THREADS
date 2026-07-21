<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dances', function (Blueprint $table) {
            $table->index('category');
            $table->index('name');
        });

        Schema::table('attires', function (Blueprint $table) {
            $table->index('municipality');
            $table->index('gender');
            $table->index(['municipality', 'gender']);
            $table->index('name_general');
        });
    }

    public function down(): void
    {
        Schema::table('dances', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['name']);
        });

        Schema::table('attires', function (Blueprint $table) {
            $table->dropIndex(['municipality']);
            $table->dropIndex(['gender']);
            $table->dropIndex(['municipality', 'gender']);
            $table->dropIndex(['name_general']);
        });
    }
};
