<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Form\FormShopStaffRequest;
use App\Http\Requests\Manager\Search\SearchShopStaffRequest;
use App\Models\ShopStaff;
use App\UseCases\ShopStaff\CreateShopStaffUseCase;
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
    public function index(
        SearchShopStaffRequest $request,
        FetchShopStaffListUseCase $useCase
    ): Response {
        $data = $useCase($request->validated(), $this->getShopId());
        return Inertia::render('ShopStaff/ShopStaffList', $data);
    }

    public function create(
        PrepareShopStaffCreateUseCase $useCase
    ): Response {
        $data = $useCase($this->getShopId());
        return Inertia::render('ShopStaff/ShopStaffForm', $data);
    }

    public function store(
        FormShopStaffRequest $request,
        CreateShopStaffUseCase $useCase,
    ): RedirectResponse {
        try {
            $validated = $request->validated();
            $useCase($validated);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '作成に失敗しました。');
        }

        return redirect()->route($this->getRoutePrefix() . '.staffs.index')->with('success', '作成に成功しました。');
    }

    public function edit(
        ShopStaff $staff,
        PrepareShopStaffEditFormUseCase $useCase
    ): Response {
        $data = $useCase($staff);
        return Inertia::render("ShopStaff/ShopStaffForm", $data);
    }

    public function update(
        FormShopStaffRequest $request,
        ShopStaff $staff,
        UpdateShopStaffUseCase $useCase
    ): RedirectResponse {
        try {
            $validated = $request->validated();
            $useCase($validated, $staff);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '更新に失敗しました。');
        }

        return redirect()->route($this->getRoutePrefix() . '.staffs.index')->with('success', '更新に成功しました。');
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

    private function getRoutePrefix(): string
    {
        return request()->is('admin/*') ? 'admin' : 'shop';
    }
}
