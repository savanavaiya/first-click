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
        Schema::create('importdatas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('brand');
            $table->string('store_name');
            $table->string('store_address');
            // $table->string('opening_time');
            // $table->string('closing_time');
            $table->string('city');
            $table->string('store_location');
            $table->string('store_location_latitude');
            $table->string('store_location_longitude');
            $table->text('diesel')->nullable()->default(null);
            $table->text('gasoline')->nullable()->default(null);
            $table->string('landmarks')->nullable();
            $table->string('brand_logo')->nullable();
            $table->string('store_image')->nullable();
            $table->double('forfil_price_diesel')->nullable();
            $table->double('forfil_price_gasoline')->nullable();
            $table->string('modify_name')->default('Firstclick');
            $table->string('status')->default('Updated');
            $table->string('custom')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('importdatas');
    }
};
