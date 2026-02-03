<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\UseCases\Log\FetchLogListUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ActivityLogController extends Controller
{
    public function index(FetchLogListUseCase $useCase): Response
    {
        $data = $useCase();
        return Inertia::render('Log/LogIndex', $data);
    }
}
