<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();  // Уникальный идентификатор
            $table->morphs('tokenable'); // Создает два столбца: tokenable_id и tokenable_type
            $table->string('name'); // Имя токена
            $table->string('token', 64)->unique(); // Хэшированный токен
            $table->text('abilities')->nullable(); // Список разрешений токена
            $table->timestamp('last_used_at')->nullable(); // Дата последнего использования
            $table->timestamp('expires_at')->nullable(); // Дата истечения действия токена
            $table->timestamps(); // Стандартные created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
