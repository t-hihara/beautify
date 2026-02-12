<?php

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
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
        Schema::create('shop_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->comment('スタッフ名');
            $table->enum('position', ShopStaffPositionTypeEnum::cases())->comment('ポジション');
            $table->text('description')->comment('スタッフ紹介');
            $table->string('image_path')->nullable()->comment('プロフィール画像');
            $table->unsignedInteger('experience_years')->comment('経歴年数');
            $table->enum('active_flag', ActiveFlagTypeEnum::cases())->comment('有効状態');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_staff');
    }
};
