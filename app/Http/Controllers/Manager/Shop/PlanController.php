<?php

namespace App\Http\Controllers\Manager\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchPlanRequest;
use App\UseCases\Plan\ExportPlanUseCase;
use App\UseCases\Plan\FetchPlanListUseCase;
use Illuminate\Http\RedirectResponse;
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

    public function exportExcel(SearchPlanRequest $request, ExportPlanUseCase $useCase): RedirectResponse
    {
        try {
            $shopId = auth('shop')->user()->shopStaff?->shop_id;
            $useCase(auth()->id(), $request->validated(), 'xlsx', $shopId);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Excelエクスポートに失敗しました。');
        }

        return back()->with('success', 'Excelエクスポートを開始しました。');
    }

    public function exportCsv(SearchPlanRequest $request, ExportPlanUseCase $useCase): RedirectResponse
    {
        try {
            $shopId = auth('shop')->user()->shopStaff?->shop_id;
            $useCase(auth()->id, $request->validated(), 'csv', $shopId);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'ExcelCsvエクスポートに失敗しました。');
        }

        return back()->with('success', 'ExcelCsvエクスポートを開始しました。');
    }
}
