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
        Schema::create('event_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // owner
            $table->string('name');
            $table->foreignId('state_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lga_id')->constrained()->cascadeOnDelete();
            $table->string('city_town')->nullable();
            $table->text('full_address');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('capacity');
            $table->decimal('starting_price', 12, 2)->nullable();
            $table->string('phone_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('hall_type')->default('indoor'); // indoor, outdoor, mixed
            $table->boolean('has_parking')->default(false);
            $table->boolean('has_generator')->default(false);
            $table->boolean('has_decoration')->default(false);
            $table->boolean('has_catering')->default(false);
            $table->json('features')->nullable(); // For advanced filtering tags (Wedding, Concert, etc)
            $table->json('images_gallery')->nullable();
            $table->string('video_preview')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('reviews_count')->default(0);
            $table->string('availability_status')->default('available'); // available, booked, maintenance
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_centers');
    }
};
