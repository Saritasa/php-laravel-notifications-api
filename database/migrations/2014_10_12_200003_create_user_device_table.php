<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUserDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UserDevice', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->unsignedInteger('user_id');
            $table->enum('deviceTypeId', ['android', 'ios']);
            $table->string('deviceToken', 500);
            $table->string('hash', 40);
            $table->timestamps();

            $table->foreign(['userId'])->references('id')->on('Users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('UserDevice', function (Blueprint $table) {
            $table->dropForeign(['userId']);
        });
        Schema::drop('UserDevice');
    }
}
