<?php

use App\Enum\ActiveFlagTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('last_name')->comment('ユーザー姓');
            $table->string('first_name')->comment('ユーザー名');
            $table->string('last_name_kana')->nullable()->comment('ユーザー姓カナ');
            $table->string('first_name_kana')->nullable()->comment('ユーザー名カナ');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->timestamp('email_verified_at')->nullable()->comment('メールアドレス認証');
            $table->string('password')->nullable()->comment('パスワード');
            $table->string('google_id')->nullable()->unique()->comment('googleId');
            $table->enum('active_flag', ActiveFlagTypeEnum::cases())->default('active')->comment('アクティブ状態');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
