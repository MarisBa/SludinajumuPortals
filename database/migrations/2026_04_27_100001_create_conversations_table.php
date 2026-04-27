<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advertisement_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->timestamp('last_message_at')->nullable();
            $table->string('last_message_preview', 200)->nullable();
            $table->unsignedInteger('buyer_unread_count')->default(0);
            $table->unsignedInteger('seller_unread_count')->default(0);
            $table->timestamps();

            $table->index('advertisement_id');
            $table->index('buyer_id');
            $table->index('seller_id');
            $table->index('last_message_at');

            $table->unique(['advertisement_id', 'buyer_id', 'seller_id'], 'unique_conversation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
