<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAndPageTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('user_and_page', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('page_id')->unsigned();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->bigInteger('user_parent')->unsigned();
            $table->foreign('user_parent')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('user_child')->unsigned();
            $table->foreign('user_child')->references('id')->on('users')->onDelete('cascade');
            $table->smallInteger('status')->default(0);
            $table->smallInteger('type')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('user_and_page');
    }
}
