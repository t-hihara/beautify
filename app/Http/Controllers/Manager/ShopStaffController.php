<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchShopStaffRequest;
use App\UseCases\ShopStaff\FetchShopStaffListUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopStaffController extends Controller
{
    public function index(
        SearchShopStaffRequest $request,
        FetchShopStaffListUseCase $useCase
    ): Response {
        $data = $useCase($request->validated());
        return Inertia::render('ShopStaff/ShopStaffList', $data);
    }
}
