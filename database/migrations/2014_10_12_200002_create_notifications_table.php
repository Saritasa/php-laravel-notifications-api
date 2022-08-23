<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Notifications', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->morphs('notifiable');
            $table->string('type');
            $table->string('title');
            $table->text('data');
            $table->timestamp('readAt')->nullable();
            $table->unsignedInteger('notificationTypeId');
            $table->timestamps();

            $table->foreign(['notificationTypeId'])->references('id')->on('NotificationTypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Notifications', function (Blueprint $table) {
            $table->dropForeign(['userId']);
            $table->dropForeign(['notificationTypeId']);
        });
        Schema::drop('Notifications');
    }
}
