<?php

namespace App\Http\Controllers\Manager\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Form\FormMenuRequest;
use App\Http\Requests\Manager\Search\SearchMenuRequest;
use App\Models\Menu;
use App\UseCases\Menu\CreateMenuUseCase;
use App\UseCases\Menu\DeleteMenuUseCase;
use App\UseCases\Menu\ExportMenuUseCase;
use App\UseCases\Menu\FetchMenuListUseCase;
use App\UseCases\Menu\PrepareMenuCreateFormUseCase;
use App\UseCases\Menu\PrepareMenuEditFormUseCase;
use App\UseCases\Menu\UpdateMenuUseCase;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class MenuController extends Controller
{
    public function index(SearchMenuRequest $request, FetchMenuListUseCase $useCase): Response
    {
        $shopId = auth('shop')->user()->shopStaff?->shop_id;
        return Inertia::render('Menu/MenuList', $useCase($request->validated(), $shopId));
    }

    public function create(PrepareMenuCreateFormUseCase $useCase): Response
    {
        $shopId = auth('shop')->user()->shopStaff?->shop_id;
        return Inertia::render('Menu/MenuForm', $useCase($shopId));
    }

    public function store(FormMenuRequest $request, CreateMenuUseCase $useCase): RedirectResponse
    {
        try {
            $data = $useCase($request->validated());
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '登録に失敗しました。');
        }

        return redirect()->route('shop.menus.index')->with('success', '登録に成功しました。');
    }

    public function edit(Menu $menu, PrepareMenuEditFormUseCase $useCase): Response
    {
        $data = $useCase($menu);
        return Inertia::render('Menu/MenuForm', $data);
    }

    public function update(FormMenuRequest $request, Menu $menu, UpdateMenuUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($request->validated(), $menu);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '更新に失敗しました。');
        }

        return redirect()->route('shop.menus.index')->with('success', '更新に成功しました。');
    }

    public function destroy(Menu $menu, DeleteMenuUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($menu);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '削除に失敗しました。');
        }

        return back()->with('success', '削除に成功しました。');
    }

    public function exportExcel(SearchMenuRequest $request, ExportMenuUseCase $useCase): RedirectResponse
    {
        try {
            $shopId = auth('shop')->user()->shopStaff?->shop_id;
            $useCase(auth()->id(), $request->validated(), 'xlsx', $shopId);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Excelエクスポートに失敗しました。');
        }

        return back()->with('success', 'Excelエクスポートに成功しました。');
    }

    public function exportCsv(SearchMenuRequest $request, ExportMenuUseCase $useCase): RedirectResponse
    {
        try {
            $shopId = auth('shop')->user()->shopStaff?->shop_id;
            $useCase(auth()->id(), $request->validated(), 'csv', $shopId);
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'CSVエクスポートに失敗しました。');
        }

        return back()->with('success', 'CSVエクスポートに成功しました。');
    }
}
