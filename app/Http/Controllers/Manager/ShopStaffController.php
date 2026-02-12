<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\UseCases\ShopStaff\FetchShopStaffListUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopStaffController extends Controller
{
    public function index(FetchShopStaffListUseCase $useCase): Response
    {
        $data = $useCase([]);
        return Inertia::render('ShopStaff/ShopStaffList', $data);
    }
}
