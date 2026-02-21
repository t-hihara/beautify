<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Search\SearchMenuRequest;
use App\UseCases\Menu\FetchMenuListUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    public function index(SearchMenuRequest $request, FetchMenuListUseCase $useCase): Response
    {
        $data = $useCase($request->validated());
        return Inertia::render('Menu/MenuList', $data);
    }
}
