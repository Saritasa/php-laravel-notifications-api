<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('user_id');
            $table->integer('notification_type_id');
            $table->boolean('is_on');
            $table->timestamps();

            $table->foreign(['user_id'])->references('id')->on('users');
            $table->foreign(['notification_type_id'])->references('id')->on('notification_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['notification_type_id']);
        });
        Schema::drop('notification_settings');
    }
}
