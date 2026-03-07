<?php

namespace App\Http\Controllers\Operator\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\Form\FormShopStaffRequest;
use App\Http\Requests\Operator\Search\SearchShopStaffRequest;
use App\Models\ShopStaff;
use App\UseCases\Operator\ShopStaff\CreateShopStaffUseCase;
use App\UseCases\Operator\ShopStaff\DeleteShopStaffUseCase;
use App\UseCases\Operator\ShopStaff\ExportShopStaffUseCase;
use App\UseCases\Operator\ShopStaff\FetchShopStaffListUseCase;
use App\UseCases\Operator\ShopStaff\PrepareShopStaffCreateUseCase;
use App\UseCases\Operator\ShopStaff\PrepareShopStaffEditFormUseCase;
use App\UseCases\Operator\ShopStaff\UpdateShopStaffUseCase;
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
