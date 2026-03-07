<?php

namespace App\Http\Controllers\Operator\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\Search\SearchLogRequest;
use App\UseCases\Operator\Log\FetchLogListUseCase;
use Inertia\Inertia;
use Inertia\Response;

class ActivityLogController extends Controller
{
    public function index(SearchLogRequest $request, FetchLogListUseCase $useCase): Response
    {
        $data = $useCase($request->validated());
        return Inertia::render('Log/LogIndex', $data);
    }
}
