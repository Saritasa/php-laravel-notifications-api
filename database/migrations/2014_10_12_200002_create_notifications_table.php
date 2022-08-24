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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->morphs('notifiable');
            $table->string('type');
            $table->string('title')->nullable();
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->unsignedInteger('notificationTypeId')->nullable();
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
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['notificationTypeId']);
        });
        Schema::drop('notifications');
    }
}