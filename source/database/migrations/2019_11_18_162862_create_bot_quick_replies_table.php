<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotQuickRepliesTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('bot_quick_replies', function (Blueprint $table) {
            $table->index('id');
            $table->string('bot_message_reply_id');
            $table->foreign('bot_message_reply_id')->references('_id')->on('bot_message_replies')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('image_url')->nullable();
            $table->string('content_type');
            $table->string('payload');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('bot_quick_replies');
    }
}
