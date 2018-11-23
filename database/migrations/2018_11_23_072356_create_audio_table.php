<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('bucket_id')->unsigned();
			$table->string('name', 100);
			$table->text('description');
			$table->date('date_taken');
			$table->string('path');
			$table->timestamps();
			
			$table->foreign('bucket_id')->references('id')->on('buckets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audio');
    }
}