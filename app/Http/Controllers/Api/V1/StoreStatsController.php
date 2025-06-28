<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\StoreStatsService;
use Illuminate\Support\Facades\Auth;

class StoreStatsController extends Controller
{
    public function __invoke(StoreStatsService $service)
    {
        $storeId = Auth::user()->store_id;

        $store = Store::withCount(['lentItems'])
            ->findOrFail($storeId);

        return response()->json([
            'totalBorrowers' => $service->totalBorrowers($store),
            'totalOutstanding' => $service->totalOutstanding($store),
            'monthlyRevenue' => $service->monthlyRevenue($store),
            'totalItemsLent' => $store->lent_items_count,
        ]);
    }
}
