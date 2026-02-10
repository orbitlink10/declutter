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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('condition', ['new', 'like_new', 'good', 'fair', 'for_parts']);
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('negotiable')->default(false);
            $table->string('county');
            $table->string('town');
            $table->string('contact_phone', 30)->nullable();
            $table->enum('status', ['draft', 'active', 'sold', 'removed'])->default('draft');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('county');
            $table->index('condition');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
