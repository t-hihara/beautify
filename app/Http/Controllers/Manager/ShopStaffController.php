<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchShopStaffRequest;
use App\UseCases\ShopStaff\ExportShopStaffUseCase;
use App\UseCases\ShopStaff\FetchShopStaffListUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ShopStaffController extends Controller
{
    public function index(
        SearchShopStaffRequest $request,
        FetchShopStaffListUseCase $useCase
    ): Response {
        $data = $useCase($request->validated(), $this->getShopId());
        return Inertia::render('ShopStaff/ShopStaffList', $data);
    }

    public function exportExcel(
        SearchShopStaffRequest $request,
        ExportShopStaffUseCase $useCase,
    ): RedirectResponse {
        try {
            $validated = $request->validated();
            $useCase(auth()->id(), $validated, $this->getShopId(), 'xlsx');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Excelエクスポートに失敗しました。');
        }

        return back()->with('success', 'Excelエクスポートに成功しました。');
    }

    public function exportCsv(
        SearchShopStaffRequest $request,
        ExportShopStaffUseCase $useCase,
    ): RedirectResponse {
        try {
            $validated = $request->validated();
            $useCase(auth()->id(), $validated, $this->getShopId(), 'csv');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Csvエクスポートに失敗しました。');
        }

        return back()->with('success', 'Csvエクスポートに成功しました。');
    }

    private function getShopId(): ?int
    {
        if (auth()->getDefaultDriver() !== 'shop') {
            return null;
        }

        return auth()->user()->shopStaff?->shop_id;
    }
}
