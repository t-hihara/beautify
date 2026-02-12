<?php

use App\Enum\DayOfWeekTypeEnum;
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
        Schema::create('shop_business_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained();
            $table->enum('day_of_week', DayOfWeekTypeEnum::cases())->comment('曜日');
            $table->time('open_time')->nullable()->comment('開始時間');
            $table->time('close_time')->nullable()->comment('終了時間');
            $table->timestamps();
            $table->unique(['shop_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_business_hours');
    }
};
