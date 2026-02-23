<?php

namespace App\Http\Controllers\Manager\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchPlanRequest;
use App\UseCases\Plan\ExportPlanUseCase;
use App\UseCases\Plan\FetchPlanListUseCase;
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
            return back()->with('error', 'CsSVクスポートに失敗しました。');
        }

        return back()->with('success', 'CSVエクスポートを開始しました。');
    }
}
