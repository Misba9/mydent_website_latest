<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('homepage_videos', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('section'); // section-1 or section-2
        $table->string('video_path');
        $table->integer('order')->nullable(); // for section-1 ordering (1, 2, 3)
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_videos');
    }
};
