<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchExportFileRequest;
use App\UseCases\ExportFile\FetchExportFileListUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ExportFileController extends Controller
{
    public function index(
        SearchExportFileRequest $request,
        FetchExportFileListUseCase $useCase,
    ): Response {
        $validated = $request->validated();
        $userId    = auth($request->attribute->get('auth_guard'))->id();
        $data      = $useCase($validated, $userId);

        return Inertia::render('Export/ExportFileList', $data);
    }
}
