<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConnectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('connects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('E670BEM_CODBEM', 20);
            $table->integer('E070EMP_CODEMP')->unsigned();
            $table->integer('R034FUN_NUMEMP')->unsigned();
            $table->integer('R034FUN_TIPCOL')->unsigned();
            $table->integer('R034FUN_NUMCAD')->unsigned();
            $table->text('obs_out')->nullable();
            $table->text('obs_in')->nullable();
            $table->timestamps();
        });

        Schema::create('connect_termo', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('connect_id')->unsigned();
            $table->integer('termo_id')->unsigned();
            $table->foreign('connect_id')
                ->references('id')
                ->on('connects')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('termo_id')
                ->references('id')
                ->on('termos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('connects');
    }
}
