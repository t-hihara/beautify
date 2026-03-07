<?php

namespace App\Http\Controllers\Operator\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\Form\FormShopRequest;
use App\Http\Requests\Operator\Search\SearchShopRequest;
use App\Models\Shop;
use App\UseCases\Operator\Shop\CreateShopUseCase;
use App\UseCases\Operator\Shop\DeleteShopUseCase;
use App\UseCases\Operator\Shop\ExportShopUseCase;
use App\UseCases\Operator\Shop\FetchShopDetailTopUseCase;
use App\UseCases\Operator\Shop\PrepareShopEditFormUseCase;
use App\UseCases\Operator\Shop\FetchShopListUseCase;
use App\UseCases\Operator\Shop\FetchShopPlansUseCase;
use App\UseCases\Operator\Shop\FetchShopStaffsUseCase;
use App\UseCases\Operator\Shop\PrepareShopCreateFormUseCase;
use App\UseCases\Operator\Shop\UpdateShopUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ShopController extends Controller
{
    public function index(SearchShopRequest $request, FetchShopListUseCase $useCase): Response
    {
        $data    = $useCase($request->validated());
        return Inertia::render('Shop/ShopList', $data);
    }

    public function create(PrepareShopCreateFormUseCase $useCase): Response
    {
        $data = $useCase();
        return Inertia::render('Shop/ShopForm', $data);
    }

    public function store(
        FormShopRequest $request,
        CreateShopUseCase $useCase
    ): RedirectResponse {
        try {
            $useCase($request->validated());
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '登録に失敗しました。');
        }

        return redirect()->route('admin.shops.index')->with('success', '登録に成功しました。');
    }

    public function show(Shop $shop, FetchShopDetailTopUseCase $useCase): Response
    {
        $data = $useCase($shop);
        return Inertia::render('Shop/ShopDetail', $data);
    }

    public function staffs(Shop $shop, FetchShopStaffsUseCase $useCase): Response
    {
        $data = $useCase($shop);
        return Inertia::render('Shop/ShopDetail', $data);
    }

    public function plans(Shop $shop, FetchShopPlansUseCase $useCase): Response
    {
        $data = $useCase($shop);
        return Inertia::render('Shop/ShopDetail', $data);
    }

    public function edit(Shop $shop, PrepareShopEditFormUseCase $useCase): Response
    {
        $data = $useCase($shop);
        return Inertia::render('Shop/ShopForm', $data);
    }

    public function update(FormShopRequest $request, Shop $shop, UpdateShopUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($request->validated(), $shop);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '更新に失敗しました。');
        }

        return redirect()->route('admin.shops.index')->with('success', '更新に成功しました。');
    }

    public function destroy(Shop $shop, DeleteShopUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($shop);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '削除に失敗しました。');
        }

        return back()->with('success', '削除に成功しました。');
    }

    public function exportExcel(SearchShopRequest $request, ExportShopUseCase $useCase): RedirectResponse
    {
        try {
            $useCase(auth()->id(), $request->validated(), 'xlsx');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Excelエクスポートに失敗しました。');
        }

        return back()->with('success', 'Excelエクスポートを開始しました。');
    }

    public function exportCsv(SearchShopRequest $request, ExportShopUseCase $useCase): RedirectResponse
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
