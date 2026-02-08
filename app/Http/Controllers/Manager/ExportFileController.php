<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchExportFileRequest;
use App\Models\ExportFile;
use App\UseCases\ExportFile\DeleteExportFileUseCase;
use App\UseCases\ExportFile\DownloadExportFileUseCase;
use App\UseCases\ExportFile\FetchExportFileListUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ExportFileController extends Controller
{
    public function index(
        SearchExportFileRequest $request,
        FetchExportFileListUseCase $useCase,
    ): Response {
        $validated = $request->validated();
        $userId    = auth($request->attributes->get('auth_guard'))->id();
        $data      = $useCase($validated, $userId);

        return Inertia::render('Export/ExportFileList', $data);
    }

    public function download(
        ExportFile $exportFile,
        DownloadExportFileUseCase $useCase
    ): StreamedResponse|RedirectResponse {
        try {
            return $useCase($exportFile);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'ダウンロードに失敗しました。');
        }
    }

    public function destroy(
        ExportFile $exportFile,
        DeleteExportFileUseCase $useCase
    ): RedirectResponse {
        try {
            $useCase($exportFile);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '削除に失敗しました。');
        }

        return back()->with('success', '削除に成功しました。');
    }
}
