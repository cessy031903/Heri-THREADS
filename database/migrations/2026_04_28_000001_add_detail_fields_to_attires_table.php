<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attires', function (Blueprint $table) {
            $table->string('material', 255)->nullable()->after('description');
            $table->text('cultural_significance')->nullable()->after('material');
        });
    }

    public function down(): void
    {
        Schema::table('attires', function (Blueprint $table) {
            $table->dropColumn(['material', 'cultural_significance']);
        });
    }
};
