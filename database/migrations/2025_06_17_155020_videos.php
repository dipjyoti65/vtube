<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->unsignedBigInteger('video_id')->autoIncrement()->primary();
            $table->string('title');
            $table->text('description')->nullable();

            // URL or storage path of the video file
            $table->string('video_path');  // Example: 'videos/user123/video1.mp4'

            // Optional: thumbnail for video
            $table->string('thumbnail_path')->nullable();

            // Optional: duration, size, etc.
            $table->integer('duration')->nullable(); // duration in seconds
            $table->bigInteger('size')->nullable();  // size in bytes

            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
