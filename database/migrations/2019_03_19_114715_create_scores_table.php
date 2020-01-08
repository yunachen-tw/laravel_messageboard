<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->bigInteger('message_id')->unsigned()->comment('被評分的訊息id');
            $table->bigInteger('user_id')->unsigned()->comment('評分者id');
            $table->integer('score')->comment('評分');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('message_id')
                ->references('message_id')
                ->on('messages')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->primary(['message_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scores', function(Blueprint $tabel){
            $tabel->dropForeign('scores_user_id_foreign');
            $tabel->dropForeign('scores_message_id_foreign');
        });
        Schema::dropIfExists('scores');
    }
}
