<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbMessagesTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('fb_messages', function (Blueprint $table) {
            $table->index('id');
            $table->string('conversation_id');
            $table->foreign('conversation_id')->references('conversation_id')->on('fb_conversations')->onDelete('cascade');
            $table->index('mid');
            $table->string('recipient_id');
            $table->string('sender_id');
            $table->text('text')->nullable();
            $table->text('attachments')->nullable();
            $table->string('reply_to_mid')->nullable();
            $table->string('sticker_id')->nullable();

            $table->string('reaction')->nullable();
            $table->string('reaction_action')->nullable();
            $table->string('reaction_emoji')->nullable();

            $table->integer('delivery_watermark')->nullable();

            $table->string('payload')->nullable();
            $table->string('quick_reply_payload')->nullable();
            $table->integer('timestamp');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('fb_messages');
    }
}
