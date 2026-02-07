<?php

use App\Enum\ExportFileStatusTypeEnum;
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
        Schema::create('export_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->comment('実行ユーザーID');
            $table->string('subject')->comment('対象');
            $table->string('filename')->comment('ファイル名');
            $table->string('file_type')->comment('ファイル形式');
            $table->string('file_path')->comment('ファイルパス');
            $table->unsignedBigInteger('file_size')->default(0)->comment('ファイルサイズ');
            $table->enum('status', ExportFileStatusTypeEnum::cases())->default(ExportFileStatusTypeEnum::PENDING)->comment('ステータス');
            $table->json('filters')->comment('検索条件');
            $table->text('error_message')->nullable()->comment('エラーメッセージ');
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_files');
    }
};
