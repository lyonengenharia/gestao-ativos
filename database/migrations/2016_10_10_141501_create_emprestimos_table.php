<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmprestimosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('emprestimos')) {
            Schema::create('emprestimos', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('E670BEM_CODBEM', 20);
                $table->integer('E070EMP_CODEMP')->unsigned();
                $table->dateTime('data_saida');
                $table->dateTime('data_entrada')->nullable();
                $table->dateTime('previsao_entrada')->nullable();
                $table->integer('R034FUN_NUMEMP')->unsigned();
                $table->integer('R034FUN_TIPCOL')->unsigned();
                $table->integer('R034FUN_NUMCAD')->unsigned();
                $table->text('obs_saida');
                $table->text('obs_entrada');
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
        Schema::dropIfExists('emprestimos');
    }
}
