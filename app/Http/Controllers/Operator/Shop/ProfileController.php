<?php

namespace App\Http\Controllers\Operator\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\UseCases\Operator\Shop\FetchShopDetailTopUseCase;
use App\UseCases\Operator\Shop\FetchShopPlansUseCase;
use App\UseCases\Operator\Shop\FetchShopStaffsUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function index(FetchShopDetailTopUseCase $useCase): Response
    {
        $shopId = auth('shop')->user()?->shop_id;
        $shop = Shop::findOrFail($shopId);
        if (!$shop) abort(403, '店舗なし');

        $data = $useCase($shop);
        return Inertia::render('Shop/ShopDetail', $data);
    }

    public function staffs(FetchShopStaffsUseCase $useCase): Response
    {
        $shopId = auth('shop')->user()?->shop_id;
        $shop = Shop::findOrFail($shopId);
        if (!$shop) abort(403, '店舗なし');

        $data = $useCase($shop);
        return Inertia::render('Shop/ShopDetail', $data);
    }

    public function plans(FetchShopPlansUseCase $useCase): Response
    {
        $shopId = auth('shop')->user()?->shop_id;
        $shop = Shop::findOrFail($shopId);
        if (!$shop) abort(403, '店舗なし');

        $data = $useCase($shop);
        return Inertia::render('Shop/ShopDetail', $data);
    }
}
