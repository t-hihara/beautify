<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchShopRequest;
use App\Models\Shop;
use App\UseCases\Shop\ExportShopUseCase;
use App\UseCases\Shop\FetchShopForEditUseCase;
use App\UseCases\Shop\FetchShopListUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ShopController extends Controller
{
    public function index(
        SearchShopRequest $request,
        FetchShopListUseCase $useCase
    ): Response {
        $filters = $request->validated();
        $data    = $useCase($filters);

        return Inertia::render('Shop/ShopList', $data);
    }

    public function edit(
        Shop $shop,
        FetchShopForEditUseCase $useCase
    ): Response {
        $data = $useCase($shop);
        return Inertia::render('Shop/ShopForm', $data);
    }

    public function exportExcel(
        SearchShopRequest $request,
        ExportShopUseCase $useCase
    ): RedirectResponse {
        try {
            $validated = $request->validated();
            $useCase(auth()->id(), $validated, 'xlsx');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Excelエクスポートに失敗しました。');
        }

        return back()->with('success', 'Excelエクスポートを開始しました。');
    }

    public function exportCsv(
        SearchShopRequest $request,
        ExportShopUseCase $useCase
    ): RedirectResponse {
        try {
            $validated = $request->validated();
            $useCase(auth()->id(), $validated, 'csv');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'CSVエクスポートに失敗しました。');
        }

        return back()->with('success', 'CSVエクスポートを開始しました。');
    }
}
