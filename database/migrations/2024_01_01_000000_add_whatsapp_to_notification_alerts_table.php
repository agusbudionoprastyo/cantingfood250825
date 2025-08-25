<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notification_alerts', function (Blueprint $table) {
            $table->boolean('whatsapp')->default(false)->after('push_notification');
            $table->text('whatsapp_message')->nullable()->after('push_notification_message');
        });
    }

    public function down()
    {
        Schema::table('notification_alerts', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'whatsapp_message']);
        });
    }
};
