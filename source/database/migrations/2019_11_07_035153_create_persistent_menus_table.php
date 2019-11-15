<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbProcessTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('fb_process', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('page_id')->unsigned();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->string('title');
            $table->string('type');
            $table->string('url')->nullable();
            $table->string('payload')->nullable();
            $table->enum('level_menu', ['1', '2', '3'])->default('1');
            $table->string('persistent_id')->nullable();
            $table->text('priority')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('fb_process');
    }
}
