<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbConversationsTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('fb_conversations', function (Blueprint $table) {
            $table->index('id');
            $table->string('user_fb_page_id');
            $table->foreign('user_fb_page_id')->references('_id')->on('user_fb_pages')->onDelete('cascade');
            $table->index('conversation_id');
            $table->text('snippet')->nullable();
            $table->integer('read_watermark')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('fb_conversations');
    }
}
