<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Benskeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('Benskeys')) {
            Schema::create('Benskeys', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('key_id')->unsigned();
                $table->integer('E670BEM_ID')->unsigned();
                $table->foreign('key_id')
                    ->references('id')
                    ->on('keys')
                    ->onDelete('cascade')
                    ->onUpde('cascade');
                $table->timestamps();
            });
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Benskeys');
    }
}
