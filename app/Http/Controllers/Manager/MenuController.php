<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\UseCases\Menu\FetchMenuListUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    public function index(FetchMenuListUseCase $useCase): Response
    {
        $data = $useCase([]);
        return Inertia::render('Menu/MenuList', $data);
    }
}
