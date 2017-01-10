<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('keys')) {
            Schema::create('keys', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('key')->unique();
                $table->string('description', 500);
                $table->integer('quantity')->default(1);
                $table->integer('in_use')->default(0);;
                $table->integer('produto_id', false, true);
                $table->foreign('produto_id')
                    ->references('id')
                    ->on('produtos')
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
        Schema::dropIfExists('keys');
    }
}
