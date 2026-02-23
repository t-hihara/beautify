<?php

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\PlanConditionTypeEnum;
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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->comment('店舗ID');
            $table->string('name')->comment('プラン名');
            $table->text('description')->comment('メニュー説明');
            $table->unsignedInteger('total_duration')->comment('総所要時間');
            $table->unsignedInteger('regular_price')->comment('定価価格');
            $table->unsignedInteger('selling_price')->comment('販売料金');
            $table->enum('condition_type', PlanConditionTypeEnum::cases())->nullable()->comment('適応条件種別');
            $table->enum('active_flag', ActiveFlagTypeEnum::cases())->default('active')->comment('公開状態');
            $table->unsignedInteger('sort_order')->comment('並び順');
            $table->date('valid_from')->nullable()->comment('期間限定: 有効期間（開始）');
            $table->date('valid_to')->nullable()->comment('期間限定: 有効期間（終了）');
            $table->timestamps();
            $table->index(['shop_id', 'active_flag', 'sort_order']);
            $table->index(['shop_id', 'condition_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
