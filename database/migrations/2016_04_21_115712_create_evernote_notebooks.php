<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvernoteNotebooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evernote_notebooks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userid');
            $table->string('token');
            $table->string('notebook_guid');
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
        Schema::drop('evernote_notebooks');
    }
}
