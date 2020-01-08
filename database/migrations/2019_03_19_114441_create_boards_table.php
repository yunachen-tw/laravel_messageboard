<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Boards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->bigIncrements('board_id')->comment('留言板編號');
            $table->bigInteger('user_id')->unsigned()->comment('留言板擁有者');
            $table->string('title')->comment('留言板標題');
            $table->string('describe')->comment('留言板敘述');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
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
        Schema::table('boards', function(Blueprint $tabel){
            $tabel->dropForeign('boards_user_id_foreign');
        });
        Schema::dropIfExists('boards');
    }
}
