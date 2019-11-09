<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('facebook_id')->nullable();
            $table->string('access_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        \App\User::updateorcreate(['id' => 1], [
            'name' => 'Khang',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123')
        ]);
        \App\User::updateorcreate(['id' => 2], [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => Hash::make('test123')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
