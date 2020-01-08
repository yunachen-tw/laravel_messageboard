<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('message_id')->comment('訊息流水號');
            $table->bigInteger('user_id')->unsigned()->comment('留言者id');
            $table->bigInteger('board_id')->unsigned()->comment('留言板編號');
            $table->string('content')->comment('訊息內容');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('board_id')
                ->references('board_id')
                ->on('boards')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function(Blueprint $tabel){
            $tabel->dropForeign('messages_user_id_foreign');
            $tabel->dropForeign('messages_board_id_foreign');
        });
        Schema::dropIfExists('messages');
    }
}
