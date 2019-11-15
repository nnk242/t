<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbConversationsTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('fb_conversations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('page_id');
            $table->foreign('page_id')->references('_id')->on('pages')->onDelete('cascade');
            $table->string('user_fb_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('profile_pic')->nullable();
            $table->string('gender')->nullable();
            $table->string('locale')->nullable();
            $table->float('timezone')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('fb_conversations');
    }
}
