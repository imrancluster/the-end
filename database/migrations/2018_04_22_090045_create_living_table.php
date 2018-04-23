<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLivingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('last_email_sent')->default(time());
            $table->tinyInteger('send_email_after')->default(15);
            $table->integer('last_email_seen')->default(time());
            $table->string('token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('livings');
    }
}
