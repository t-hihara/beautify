<?php

namespace App\Http\Controllers\Manager\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Form\FormPlanRequest;
use App\Http\Requests\Manager\Search\SearchPlanRequest;
use App\UseCases\Plan\CreatePlanUseCase;
use App\UseCases\Plan\DeletePlanUseCase;
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
        $shopId = auth('shop')->user()?->shop_id;
        return Inertia::render('Plan/PlanList', $useCase($request->validated(), $shopId));
    }

    public function create(PreparePlanCreateFormUseCase $useCase): Response
    {
        $shopId = auth('shop')->user()?->shop_id;
        return Inertia::render('Plan/PlanForm', $useCase($shopId));
    }

    public function store(FormPlanRequest $request, CreatePlanUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($request->validated());
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '登録に失敗しました。');
        }

        return redirect()->route('admin.plans.index')->with('success', '登録に成功しました。');
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

    public function destroy(Plan $plan, DeletePlanUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($plan);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '削除に失敗しました。');
        }

        return back()->with('success', '削除に成功しました。');
    }

    public function exportExcel(SearchPlanRequest $request, ExportPlanUseCase $useCase): RedirectResponse
    {
        try {
            $shopId = auth('shop')->user()?->shop_id;
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
            $shopId = auth('shop')->user()?->shop_id;
            $useCase(auth()->id(), $request->validated(), 'csv', $shopId);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'CSVエクスポートに失敗しました。');
        }

        return back()->with('success', 'CSVエクスポートを開始しました。');
    }
}
