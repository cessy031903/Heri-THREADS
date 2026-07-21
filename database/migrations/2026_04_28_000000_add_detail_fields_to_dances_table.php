<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dances', function (Blueprint $table) {
            $table->string('region', 255)->nullable()->after('description');
            $table->string('origin', 255)->nullable()->after('region');
            $table->text('cultural_meaning')->nullable()->after('origin');
            $table->text('historical_background')->nullable()->after('cultural_meaning');
        });
    }

    public function down(): void
    {
        Schema::table('dances', function (Blueprint $table) {
            $table->dropColumn(['region', 'origin', 'cultural_meaning', 'historical_background']);
        });
    }
};
