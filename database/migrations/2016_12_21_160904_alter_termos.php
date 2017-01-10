<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTermos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('termos', function (Blueprint $table) {
            $table->dropColumn(['maketermo', 'pathtermo']);
            $table->dateTime('notification_of_send')->nullable();
            $table->dateTime('receipt')->nullable();
            $table->text('obs')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
