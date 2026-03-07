<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller
{
    public function options(Shop $shop): JsonResponse
    {
        $menus = Menu::where('shop_id', $shop->id)
            ->orderBy('sort_order')
            ->get()
            ->groupBy(fn($menu) => $menu->type->description())
            ->map(fn($menus, $label) => [
                'label' => $label,
                'items' => $menus->map(fn($menu) => [
                    'id'       => $menu->id,
                    'shopId'   => $menu->shop_id,
                    'name'     => $menu->name,
                    'type'     => $menu->type->value,
                    'price'    => $menu->price,
                    'duration' => $menu->duration,
                ])->values()->toArray(),
            ])->values()->toArray();

        return response()->json([
            'menus' => $menus
        ], Response::HTTP_OK);
    }
}
