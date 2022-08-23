<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('NotificationSettings', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->unsignedInteger('userId');
            $table->boolean('isOn');
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
        Schema::table('notificationSettings', function (Blueprint $table) {
            $table->dropForeign(['userId']);
            $table->dropForeign(['notificationTypeId']);
        });
        Schema::drop('notificationSettings');
    }
}
