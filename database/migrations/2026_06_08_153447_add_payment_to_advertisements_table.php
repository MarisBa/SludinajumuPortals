<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('published');
            $table->string('stripe_session_id')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('stripe_session_id');

            // From now on new ads start unpublished until they're paid.
            $table->integer('published')->default(0)->change();
        });

        // Backfill: anything already published predates this migration and is
        // therefore considered "paid" — otherwise the UI would prompt every
        // existing owner to pay again.
        DB::table('advertisements')
            ->where('published', 1)
            ->update([
                'payment_status' => 'paid',
                'paid_at'        => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'stripe_session_id', 'paid_at']);
            $table->integer('published')->default(1)->change();
        });
    }
};
