<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftsTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('gifts', function (Blueprint $table) {
            $table->index('id');
            $table->string('bot_message_head_id')->nullable();
            $table->foreign('bot_message_head_id')->references('_id')->on('bot_message_heads')->onDelete('cascade');
            $table->string('code')->nullable();
            $table->integer('amount')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('gifts');
    }
}
