<?php

namespace App\Http\Controllers\Api;

use App\Enum\ActiveFlagTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $name   = $request->query('name');
        $limit  = $request->input('limit');
        $offset = $request->input('offset');

        \Log::info($request->toArray());

        $shops = Shop::where('active_flag', ActiveFlagTypeEnum::ACTIVE)
            ->when($name !== null && $name !== '', fn($q) => $q->where('name', 'like', '%' . $name . '%'))
            ->orderBy('id')
            ->limit($limit ?? 4)
            ->offset($offset ?? 0)
            ->get(['id', 'name']);

        return response()->json([
            'shops' => $shops,
        ], 200);
    }
}
