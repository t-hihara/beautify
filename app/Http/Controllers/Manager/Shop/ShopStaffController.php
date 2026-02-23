<?php

namespace App\Http\Controllers\Manager\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Form\FormShopStaffRequest;
use App\Http\Requests\Manager\Search\SearchShopStaffRequest;
use App\Models\ShopStaff;
use App\UseCases\ShopStaff\CreateShopStaffUseCase;
use App\UseCases\ShopStaff\DeleteShopStaffUseCase;
use App\UseCases\ShopStaff\ExportShopStaffUseCase;
use App\UseCases\ShopStaff\FetchShopStaffListUseCase;
use App\UseCases\ShopStaff\PrepareShopStaffCreateUseCase;
use App\UseCases\ShopStaff\PrepareShopStaffEditFormUseCase;
use App\UseCases\ShopStaff\UpdateShopStaffUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ShopStaffController extends Controller
{
    public function index(SearchShopStaffRequest $request, FetchShopStaffListUseCase $useCase): Response
    {
        $shopId = auth('shop')->user()->shopStaff?->shop_id;
        $data   = $useCase($request->validated(), $shopId);
        return Inertia::render('ShopStaff/ShopStaffList', $data);
    }

    public function create(PrepareShopStaffCreateUseCase $useCase): Response
    {
        $data = $useCase($this->getShopId());
        return Inertia::render('ShopStaff/ShopStaffForm', $data);
    }

    public function store(FormShopStaffRequest $request, CreateShopStaffUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($request->validated());
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '作成に失敗しました。');
        }

        return redirect()->route('shop.staffs.index')->with('success', '作成に成功しました。');
    }

    public function edit(ShopStaff $staff, PrepareShopStaffEditFormUseCase $useCase): Response
    {
        $data = $useCase($staff);
        return Inertia::render("ShopStaff/ShopStaffForm", $data);
    }

    public function update(
        FormShopStaffRequest $request,
        ShopStaff $staff,
        UpdateShopStaffUseCase $useCase
    ): RedirectResponse {
        try {
            $useCase($request->validated(), $staff);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '更新に失敗しました。');
        }

        return redirect()->route('shop.staffs.index')->with('success', '更新に成功しました。');
    }

    public function destroy(ShopStaff $staff, DeleteShopStaffUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($staff);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '削除に失敗しました。');
        }

        return back()->with('success', '削除に成功しました。');
    }

    public function exportExcel(
        SearchShopStaffRequest $request,
        ExportShopStaffUseCase $useCase,
    ): RedirectResponse {
        try {
            $shopId = auth('shop')->user()->shopStaff?->shop_id;
            $useCase(auth()->id(), $request->validated(), 'xlsx', $shopId);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Excelエクスポートに失敗しました。');
        }

        return back()->with('success', 'Excelエクスポートに成功しました。');
    }

    public function exportCsv(SearchShopStaffRequest $request, ExportShopStaffUseCase $useCase): RedirectResponse
    {
        try {
            $shopId = auth('shop')->user()->shopStaff?->shop_id;
            $useCase(auth()->id(), $request->validated(), 'csv', $shopId);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Csvエクスポートに失敗しました。');
        }

        return back()->with('success', 'Csvエクスポートに成功しました。');
    }
}
