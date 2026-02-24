<?php

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->comment('店舗ID');
            $table->string('name')->comment('メニュー名');
            $table->enum('type', MenuTypeEnum::values())->comment('カテゴリ');
            $table->unsignedInteger('price')->comment('料金（税別）');
            $table->unsignedInteger('duration')->comment('所要時間');
            $table->text('description')->nullable()->comment('メニュー説明');
            $table->enum('active_flag', ActiveFlagTypeEnum::values())->comment('公開状態');
            $table->unsignedInteger('sort_order')->comment('並び順');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
