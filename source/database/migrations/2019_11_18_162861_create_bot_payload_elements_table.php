<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotPayloadElementsTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('bot_payload_elements', function (Blueprint $table) {
            $table->index('id');
            $table->string('bot_message_reply_id');
            $table->foreign('bot_message_reply_id')->references('_id')->on('bot_message_replies')->onDelete('cascade');
            $table->string('title');
            $table->string('template_type');
            $table->string('image_url');
            $table->string('subtitle');
            $table->string('default_action_type')->nullable();
            $table->string('default_action_url')->nullable();
            $table->boolean('default_action_messenger_extensions')->nullable();
            $table->string('default_action_messenger_webview_height_ratio')->nullable();
            $table->integer('group');
            $table->integer('position');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('bot_payload_elements');
    }
}
