<?php

namespace App\Http\Controllers\Manager\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Form\FormShopStaffRequest;
use App\Http\Requests\Manager\Search\SearchShopStaffRequest;
use App\Models\ShopStaff;
use App\UseCases\Manager\ShopStaff\CreateShopStaffUseCase;
use App\UseCases\Manager\ShopStaff\DeleteShopStaffUseCase;
use App\UseCases\Manager\ShopStaff\ExportShopStaffUseCase;
use App\UseCases\Manager\ShopStaff\FetchShopStaffListUseCase;
use App\UseCases\Manager\ShopStaff\PrepareShopStaffCreateUseCase;
use App\UseCases\Manager\ShopStaff\PrepareShopStaffEditFormUseCase;
use App\UseCases\Manager\ShopStaff\UpdateShopStaffUseCase;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ShopStaffController extends Controller
{
    public function index(SearchShopStaffRequest $request, FetchShopStaffListUseCase $useCase): Response
    {
        return Inertia::render('ShopStaff/ShopStaffList', $useCase($request->validated()));
    }

    public function create(PrepareShopStaffCreateUseCase $useCase): Response
    {
        return Inertia::render('ShopStaff/ShopStaffForm', $useCase());
    }

    public function store(FormShopStaffRequest $request, CreateShopStaffUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($request->validated());
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '作成に失敗しました。');
        }

        return redirect()->route('admin.staffs.index')->with('success', '作成に成功しました。');
    }

    public function edit(ShopStaff $staff, PrepareShopStaffEditFormUseCase $useCase): Response
    {
        return Inertia::render("ShopStaff/ShopStaffForm", $useCase($staff));
    }

    public function update(FormShopStaffRequest $request, ShopStaff $staff, UpdateShopStaffUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($request->validated(), $staff);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '更新に失敗しました。');
        }

        return redirect()->route('admin.staffs.index')->with('success', '更新に成功しました。');
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

    public function exportExcel(SearchShopStaffRequest $request, ExportShopStaffUseCase $useCase): RedirectResponse
    {
        try {
            $useCase(auth()->id(), $request->validated(), 'xlsx');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Excelエクスポートに失敗しました。');
        }

        return back()->with('success', 'Excelエクスポートに成功しました。');
    }

    public function exportCsv(SearchShopStaffRequest $request, ExportShopStaffUseCase $useCase): RedirectResponse
    {
        try {
            $useCase(auth()->id(), $request->validated(), 'csv');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Csvエクスポートに失敗しました。');
        }

        return back()->with('success', 'Csvエクスポートに成功しました。');
    }
}
