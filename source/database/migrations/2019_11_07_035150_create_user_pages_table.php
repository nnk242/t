<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPagesTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('user_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->index('user_page_id');
            $table->string('page_id');
            $table->foreign('page_id')->references('_id')->on('pages')->onDelete('cascade');
            $table->string('access_token', 255);
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('_id')->on('users')->onDelete('cascade');
            $table->smallInteger('status')->default(1);
            $table->smallInteger('run_conversations')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('user_pages');
    }
}
