<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGbPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups_buckets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
			$table->integer('bucket_id')->unsigned();
			
			$table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
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
        Schema::dropIfExists('groups_buckets');
    }
}
