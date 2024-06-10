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
        Schema::create('pricechangereqs', function (Blueprint $table) {
            $table->id();
            $table->string('modify_name');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('station_id');
            $table->string('diesel');
            $table->string('gasoline');
            $table->string('detail_photo')->nullable();
            $table->string('comments')->nullable();
            $table->string('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricechangereqs');
    }
};
