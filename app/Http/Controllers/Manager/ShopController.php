<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Form\FormShopRequest;
use App\Http\Requests\Manager\Search\SearchShopRequest;
use App\Models\Shop;
use App\UseCases\Shop\CreateShopUseCase;
use App\UseCases\Shop\DeleteShopUseCase;
use App\UseCases\Shop\ExportShopUseCase;
use App\UseCases\Shop\PrepareShopEditFormUseCase;
use App\UseCases\Shop\FetchShopListUseCase;
use App\UseCases\Shop\PrepareShopCreateFormUseCase;
use App\UseCases\Shop\UpdateShopUseCase;
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

    public function create(
        PrepareShopCreateFormUseCase $useCase
    ): Response {
        $data = $useCase();
        return Inertia::render('Shop/ShopForm', $data);
    }

    public function store(
        FormShopRequest $request,
        CreateShopUseCase $useCase
    ): RedirectResponse {
        try {
            $validated = $request->validated();
            $useCase($validated);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '登録に失敗しました。');
        }

        return redirect()->route('admin.shops.index')->with('success', '登録に成功しました。');
    }

    public function edit(
        Shop $shop,
        PrepareShopEditFormUseCase $useCase
    ): Response {
        $data = $useCase($shop);
        return Inertia::render('Shop/ShopForm', $data);
    }

    public function update(
        FormShopRequest $request,
        Shop $shop,
        UpdateShopUseCase $useCase
    ): RedirectResponse {
        try {
            $validated = $request->validated('shop');
            $useCase($validated, $shop);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '更新に失敗しました。');
        }

        return redirect()->route('admin.shops.index')->with('success', '更新に成功しました。');
    }

    public function destroy(
        Shop $shop,
        DeleteShopUseCase $useCase
    ): RedirectResponse {
        try {
            $useCase($shop);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '削除に失敗しました。');
        }

        return back()->with('success', '削除に成功しました。');
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
