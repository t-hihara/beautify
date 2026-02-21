<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Form\FormMenuRequest;
use App\Http\Requests\Manager\Search\SearchMenuRequest;
use App\Models\Menu;
use App\UseCases\Menu\ExportMenuUseCase;
use App\UseCases\Menu\FetchMenuListUseCase;
use App\UseCases\Menu\PrepareMenuEditFormUseCase;
use App\UseCases\Menu\UpdateMenuUseCase;
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

    public function edit(Menu $menu, PrepareMenuEditFormUseCase $useCase): Response
    {
        $data = $useCase($menu);
        return Inertia::render('Menu/MenuForm', $data);
    }

    public function update(FormMenuRequest $request, Menu $menu, UpdateMenuUseCase $useCase): RedirectResponse
    {
        try {
            $useCase($request->validated());
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', '更新に失敗しました。');
        }

        return redirect()->route($this->getRoutePrefix() . '.menus.index') - with('success', '更新に成功しました。');
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

    private function getRoutePrefix(): string
    {
        return request()->is('admin/*') ? 'admin' : 'shop';
    }
}
