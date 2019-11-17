<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbProcessTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('fb_process', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('data');
            $table->integer('code')->nullable();
            $table->string('message')->nullable();
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('fb_process');
    }
}
