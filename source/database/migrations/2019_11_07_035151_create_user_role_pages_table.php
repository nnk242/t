<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolePagesTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('user_role_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_page_id')->unsigned();
            $table->foreign('user_page_id')->references('_id')->on('user_pages')->onDelete('cascade');
            $table->bigInteger('user_parent')->unsigned();
            $table->foreign('user_parent')->references('_id')->on('users')->onDelete('cascade');
            $table->bigInteger('user_child')->unsigned();
            $table->foreign('user_child')->references('_id')->on('users')->onDelete('cascade');
            $table->smallInteger('status')->default(0);
            $table->smallInteger('type')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('user_role_pages');
    }
}
