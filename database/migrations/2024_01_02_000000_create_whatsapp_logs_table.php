<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('phone_number');
            $table->string('country_code', 5);
            $table->text('message');
            $table->enum('status', ['success', 'failed', 'pending'])->default('pending');
            $table->text('response')->nullable();
            $table->text('error_message')->nullable();
            $table->string('gateway_used')->default('whatsapp');
            $table->json('metadata')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('frontend_orders')->onDelete('cascade');
            $table->index(['order_id', 'status']);
            $table->index(['phone_number', 'status']);
            $table->index('sent_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
