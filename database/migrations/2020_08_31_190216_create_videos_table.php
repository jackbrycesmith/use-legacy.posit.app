<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->uuid('uuid');

            // Converted details
            $table->string('path')->nullable();
            $table->string('disk')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('seconds')->nullable();
            $table->unsignedBigInteger('size')->nullable();

            // Auto Extracted Poster image
            $table->string('poster_path')->nullable();
            $table->string('poster_disk')->nullable();

            // Tmp details
            $table->string('tmp_path')->nullable();
            $table->string('tmp_disk')->nullable();
            $table->string('tmp_mime_type')->nullable();
            $table->unsignedBigInteger('tmp_size')->nullable();

            $table->json('meta')->nullable();
            $table->datetime('downloadable_at')->nullable(); // When normalised/converted from tmp file user upload
            $table->datetime('streamable_at')->nullable(); // When converted to HLS
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
