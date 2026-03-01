<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\UseCases\Public\FetchHomeUseCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(FetchHomeUseCase $useCase): Response
    {
        return Inertia::render('Public/Home', $useCase());
    }
}
