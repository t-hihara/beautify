<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchLogRequest;
use App\UseCases\Log\FetchLogListUseCase;
use Inertia\Inertia;
use Inertia\Response;

class ActivityLogController extends Controller
{
    public function index(SearchLogRequest $request, FetchLogListUseCase $useCase): Response
    {
        $validated = $request->validated();
        $data      = $useCase($validated);

        return Inertia::render('Log/LogIndex', $data);
    }
}
