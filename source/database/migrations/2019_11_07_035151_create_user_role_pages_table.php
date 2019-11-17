<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolePagesTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('user_role_pages', function (Blueprint $table) {
            $table->index('id');
            $table->index('fb_page_parent');
            $table->string('fb_page_id');
            $table->foreign('fb_page_id')->references('fb_page_id')->on('pages')->onDelete('cascade');
            $table->string('user_parent');
            $table->foreign('user_parent')->references('_id')->on('users')->onDelete('cascade');
            $table->string('user_child');
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
