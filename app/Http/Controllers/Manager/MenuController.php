<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchMenuRequest;
use App\UseCases\Menu\ExportMenuUseCase;
use App\UseCases\Menu\FetchMenuListUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class MenuController extends Controller
{
    public function index(SearchMenuRequest $request, FetchMenuListUseCase $useCase): Response
    {
        $data = $useCase($request->validated(), $this->getShopId());
        return Inertia::render('Menu/MenuList', $data);
    }

    public function exportExcel(SearchMenuRequest $request, ExportMenuUseCase $useCase): RedirectResponse
    {
        try {
            $useCase(auth()->id(), $request->validated(), $this->getShopId(), 'xlsx');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Excelエクスポートに失敗しました。');
        }

        return back()->with('success', 'Excelエクスポートに成功しました。');
    }

    public function exportCsv(SearchMenuRequest $request, ExportMenuUseCase $useCase): RedirectResponse
    {
        try {
            $useCase(auth()->id(), $request->validated(), $this->getShopId(), 'csv');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'CSVエクスポートに失敗しました。');
        }

        return back()->with('success', 'CSVエクスポートに成功しました。');
    }

    private function getShopId(): ?int
    {
        if (auth()->getDefaultDriver() !== 'shop') {
            return null;
        }

        return auth()->user()->shopStaff?->shop_id;
    }
}
