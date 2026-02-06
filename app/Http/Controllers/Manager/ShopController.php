<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchShopRequest;
use App\UseCases\Shop\FetchShopListUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    public function index(
        SearchShopRequest $request,
        FetchShopListUseCase $useCase
    ): Response {
        $filters = $request->validated();
        $data    = $useCase($filters);

        return Inertia::render('Shop/ShopList', $data);
    }
}
