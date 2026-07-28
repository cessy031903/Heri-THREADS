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
        Schema::table('dances', function (Blueprint $table) {
            $table->string('municipality', 50)->nullable()->after('category');
            $table->index('municipality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dances', function (Blueprint $table) {
            $table->dropIndex(['municipality']);
            $table->dropColumn('municipality');
        });
    }
};
