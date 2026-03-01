<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\Search\SearchShopRequest;
use App\UseCases\Public\FetchShopsUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    public function index(SearchShopRequest $request, FetchShopsUseCase $useCase): Response
    {
        return Inertia::render('Public/ShopList', $useCase($request->validated()));
    }
}
