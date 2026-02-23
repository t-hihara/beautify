<?php

namespace App\Http\Controllers\Manager\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Plan\FetchPlanListUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlanController extends Controller
{
    public function index(FetchPlanListUseCase $useCase): Response
    {
        $data = $useCase([]);
        return Inertia::render('Plan/PlanList', $data);
    }
}
