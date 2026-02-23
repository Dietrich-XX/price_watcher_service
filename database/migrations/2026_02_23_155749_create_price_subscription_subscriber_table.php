<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Решил добавить отношения Многие-к-многим для возможности пользователем-подписчиком добавить еще 1 url для подписки на цену
        Schema::create('price_subscription_subscriber', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('price_subscription_id')
                ->constrained('price_subscriptions')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['subscriber_id', 'price_subscription_id'], 'subscription_subscriber_unique');; //для уникальности пары
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_subscription_subscriber');
    }
};
