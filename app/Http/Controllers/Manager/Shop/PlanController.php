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
        $shopId = auth('shop')->user()->shopStaff?->shop_id;
        $data   = $useCase($request->validated(), $shopId);
        return Inertia::render('Plan/PlanList', $data);
    }
}
