<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmazonUrlToNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->string("amazon_url",2000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            //$table->dropColumn('amazon_url');
        });
    }
}
