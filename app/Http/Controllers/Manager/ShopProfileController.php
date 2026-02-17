<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\UseCases\Shop\FetchShopStaffsUseCase;
use App\UseCases\Shop\FetchShopUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopProfileController extends Controller
{
    public function index(FetchShopUseCase $useCase): Response
    {
        $shop = Shop::findOrFail($this->getShopId());
        if (!$shop) abort(403, '店舗なし');

        $data = $useCase($shop);
        return Inertia::render('Shop/ShopDetail', $data);
    }

    public function staffs(FetchShopStaffsUseCase $useCase): Response
    {
        $shop = Shop::findOrFail($this->getShopId());
        if (!$shop) abort(403, '店舗なし');

        $data = $useCase($shop);
        return Inertia::render('Shop/ShopDetail', $data);
    }

    private function getShopId(): ?int
    {
        if (auth()->getDefaultDriver() !== 'shop') {
            return null;
        }

        return auth()->user()->shopStaff?->shop_id;
    }
}
