<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The original `dances.category` enum ('pagaddut', 'hinggatut', 'dinuy-a')
 * never matched the values the admin form actually saves (the 11 Ifugao
 * municipality slugs — 'banaue', 'kiangan', etc., see ManageDances::rules()).
 * SQLite doesn't enforce enum constraints so this went unnoticed; it would
 * reject every insert on MySQL/PostgreSQL. Widening to a plain string here
 * so the column matches what the application has actually been writing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dances', function (Blueprint $table) {
            $table->dropIndex(['category']);
        });

        Schema::table('dances', function (Blueprint $table) {
            $table->string('category_new', 50)->nullable()->after('category');
        });

        DB::table('dances')->update(['category_new' => DB::raw('category')]);

        Schema::table('dances', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        Schema::table('dances', function (Blueprint $table) {
            $table->renameColumn('category_new', 'category');
        });

        Schema::table('dances', function (Blueprint $table) {
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::table('dances', function (Blueprint $table) {
            $table->dropIndex(['category']);
        });

        Schema::table('dances', function (Blueprint $table) {
            $table->string('category_old', 50)->nullable()->after('category');
        });

        DB::table('dances')->update(['category_old' => DB::raw('category')]);

        Schema::table('dances', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        Schema::table('dances', function (Blueprint $table) {
            $table->renameColumn('category_old', 'category');
        });

        Schema::table('dances', function (Blueprint $table) {
            $table->index('category');
        });
    }
};
