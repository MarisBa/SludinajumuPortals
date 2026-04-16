<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 30)->unique()->nullable()->after('name');
            $table->text('bio')->nullable()->after('username');
            $table->string('location', 100)->nullable()->after('address');
            $table->string('phone', 20)->nullable()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->json('notification_prefs')->nullable()->after('two_factor_confirmed_at');
            $table->json('privacy_prefs')->nullable()->after('notification_prefs');
            $table->timestamp('password_changed_at')->nullable()->after('privacy_prefs');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'bio', 'location', 'phone', 'phone_verified_at',
                'notification_prefs', 'privacy_prefs', 'password_changed_at',
            ]);
            $table->dropSoftDeletes();
        });
    }
};
