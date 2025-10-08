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
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            
            // Link to the user who created the post
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Link to the Category (NEW REQUIREMENT)
            // It will reference the 'categories' table. It is set to nullable so you can save a post 
            // even if a category is deleted, and its posts will just become uncategorized.
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            
            // Core Post Data
            $table->string('title', 255);
            $table->text('body');
            $table->string('feature_image')->nullable(); // Image is optional
            $table->boolean('published')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_posts');
    }
};
