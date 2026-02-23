<?php

namespace App\Http\Controllers\Manager\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchPlanRequest;
use App\UseCases\Plan\FetchPlanListUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlanController extends Controller
{
    public function index(SearchPlanRequest $request, FetchPlanListUseCase $useCase): Response
    {
        $data = $useCase($request->validated());
        return Inertia::render('Plan/PlanList', $data);
    }
}
