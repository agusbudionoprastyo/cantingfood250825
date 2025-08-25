<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('template_content');
            $table->json('variables')->nullable();
            $table->enum('type', ['order_notification', 'custom', 'marketing'])->default('order_notification');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['slug', 'is_active']);
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_templates');
    }
};
