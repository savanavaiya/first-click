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
        Schema::create('storeimgforapps', function (Blueprint $table) {
            $table->id();
            $table->string('modify_name');
            $table->unsignedBigInteger('station_id');
            $table->string('storefornt_img');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storeimgforapps');
    }
};
