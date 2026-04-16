<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->json('images')->nullable()->after('second_image');
            $table->string('feature_image')->nullable()->change();
            $table->string('first_image')->nullable()->change();
            $table->string('second_image')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
