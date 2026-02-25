<?php

namespace App\Http\Controllers\Manager\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Form\FormPlanRequest;
use App\Http\Requests\Manager\Search\SearchPlanRequest;
use App\Models\Plan;
use App\UseCases\Plan\ExportPlanUseCase;
use App\UseCases\Plan\FetchPlanListUseCase;
use App\UseCases\Plan\PreparePlanCreateFormUseCase;
use App\UseCases\Plan\PreparePlanEditFormUseCase;
use App\UseCases\Plan\UpdatePlanUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class PlanController extends Controller
{
    public function index(SearchPlanRequest $request, FetchPlanListUseCase $useCase): Response
    {
        $data = $useCase($request->validated());
        return Inertia::render('Plan/PlanList', $data);
    }

    public function create(PreparePlanCreateFormUseCase $useCase): Response
    {
        $data = $useCase();
        return Inertia::render('Plan/PlanForm', $data);
    }

    public function edit(Plan $plan, PreparePlanEditFormUseCase $useCase): Response
    {
        $data = $useCase($plan);
        return Inertia::render('Plan/PlanForm', $data);
    }

    public function update(FormPlanRequest $request, Plan $plan, UpdatePlanUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($request->validated(), $plan);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '更新に失敗しました。');
        }

        return redirect()->route('admin.plans.index')->with('success', '更新に成功しました。');
    }

    public function exportExcel(SearchPlanRequest $request, ExportPlanUseCase $useCase): RedirectResponse
    {
        try {
            $useCase(auth()->id(), $request->validated(), 'xlsx');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Excelエクスポートに失敗しました。');
        }

        return back()->with('success', 'Excelエクスポートを開始しました。');
    }

    public function exportCsv(SearchPlanRequest $request, ExportPlanUseCase $useCase): RedirectResponse
    {
        try {
            $useCase(auth()->id(), $request->validated(), 'csv');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'CSVエクスポートに失敗しました。');
        }

        return back()->with('success', 'CSVエクスポートを開始しました。');
    }
}
