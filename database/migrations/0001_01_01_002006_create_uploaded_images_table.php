<?php

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
        Schema::create('uploaded_images', function (Blueprint $table) {
            $table->id();
            $table->string('disk')->comment('ストレージディスク');
            $table->string('file_path')->comment('ファイルパス');
            $table->string('file_name')->comment('ファイル名');
            $table->string('mime_type')->comment('ファイルタイプ');
            $table->unsignedBigInteger('file_size')->default(0)->comment('ファイルサイズ');
            $table->unsignedBigInteger('imageable_id')->comment('親ID');
            $table->string('imageable_type')->comment('親モデルクラス');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploaded_images');
    }
};
