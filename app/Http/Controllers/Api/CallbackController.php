<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Methods\BitlogaExchanger;
use App\Services\Methods\LuckyPayExchanger;
use App\Services\Methods\RacksExchanger;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function racks(Request $request, RacksExchanger $racksExchanger)
    {
        $racksExchanger->handleCallback($request->json());
        return response()->json(['message' => "Transaction is get"], 200);
    }

    public function bitloga(Request $request, BitlogaExchanger $bitlogaExchanger)
    {
        $bitlogaExchanger->handleCallback($request->json());
        return response()->json(['message' => "Transaction is get"], 200);
    }

    public function luckypay(Request $request, LuckyPayExchanger $luckyPayExchanger)
    {
        $luckyPayExchanger->handleCallback($request->input('payload'));
        return response()->json(['message' => "Transaction is get"], 200);
    }
}
