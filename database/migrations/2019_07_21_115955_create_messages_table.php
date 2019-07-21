<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedTinyInteger('type');
            $table->longText('message')->nullable();
            $table->string('action')->nullable();
            $table->string('media')->nullable();
            $table->json('mentions')->default('{}');
            $table->json('metadata')->default('{}');
            $table->timestamps();
        });
    }
}
