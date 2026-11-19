<?php

namespace App\Http\Controllers;

use App\Services\MapitService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected MapitService $mapitService;

    public function __construct(MapitService $mapitService)
    {
        $this->mapitService = $mapitService;
    }

    /**
     * MapIt Webhook for automatic delivery tracking updates.
     */
    public function mapitWebhook(Request $request)
    {
        $payload = $request->all();
        $result = $this->mapitService->handleWebhook($payload);

        return response()->json($result);
    }
}

