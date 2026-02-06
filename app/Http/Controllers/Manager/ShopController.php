<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\UseCases\Shop\FetchShopListUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    public function index(FetchShopListUseCase $useCase): Response
    {
        $data = $useCase();
        return Inertia::render('Shop/ShopList', $data);
    }
}
