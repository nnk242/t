<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::connection('mongodb')->create('events', function (Blueprint $table) {
            $table->index('id');
            $table->string('fb_page_id');
            $table->foreign('fb_page_id')->references('fb_page_id')->on('pages')->onDelete('cascade');
            ##normal
            $table->text('text')->nullable();

            ##Type
            $table->string('type');

            ##Timer
            $table->integer('begin_time_open')->nullable();
            $table->integer('end_time_open')->nullable();
            $table->integer('begin_time_active')->nullable();
            $table->integer('end_time_active')->nullable();
            ##Status
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('events');
    }
}
