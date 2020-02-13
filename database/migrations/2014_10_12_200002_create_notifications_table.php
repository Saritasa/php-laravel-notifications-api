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
            $table->increments('id')->index();
            $table->morphs('notifiable');
            $table->string('type');
            $table->string('title');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->unsignedInteger('notification_type_id');
            $table->timestamps();

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
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['notification_type_id']);
        });
        Schema::drop('notifications');
    }
}
