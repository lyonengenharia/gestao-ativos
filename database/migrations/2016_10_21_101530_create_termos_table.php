<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('termos')) {
            Schema::create('termos', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('tipotermo_id')->unsigned();
                $table->boolean('maketermo')->default(false);
                $table->string('pathtermo', 255)->nullable();
                $table->boolean('signtermo')->default(false);
                $table->string('pathsigntermo', 255)->nullable();
                $table->foreign('tipotermo_id')
                    ->references('id')
                    ->on('tipotermos')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
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
        Schema::dropIfExists('termos');
    }
}
