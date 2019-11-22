<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotElementButtonsTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('bot_element_buttons', function (Blueprint $table) {
            $table->index('id');
            $table->string('bot_payload_element_id');
            $table->foreign('bot_payload_element_id')->references('_id')->on('bot_payload_elements')->onDelete('cascade');
            $table->string('type');
            $table->string('url')->nullable();
            $table->string('title');
            $table->string('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('bot_payload_buttons');
    }
}
