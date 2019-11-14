<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMessagesTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('user_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('a');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('user_messages');
    }
}
