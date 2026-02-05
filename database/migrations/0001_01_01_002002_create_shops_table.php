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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('店舗名');
            $table->string('email')->unique()->comment('店舗メールアドレス');
            $table->string('phone')->comment('店舗電話番号');
            $table->foreignId('prefecture_id')->constrained();
            $table->string('zipcode', 7)->comment('郵便番号');
            $table->string('address')->comment('住所');
            $table->string('building')->nullable()->comment('番地・建物名');
            $table->text('description')->nullable()->comment('店舗説明');
            $table->enum('active_flag', ActiveFlagTypeEnum::cases())->comment('営業状態');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
