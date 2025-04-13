<?php

namespace App\Services\Currency;

use App\Models\Currency;
use App\Models\CurrencyPrice;
use App\Traits\Curl;

class CurrencyService
{
    use Curl;

    private string $url;

    public function __construct()
    {
        $this->url = config('keys.coingecko_api_url') . "/api/v3/simple/price";;
    }

    public function updateCurrencies(): void
    {
        $response = $this->curlGet($this->url, $this->constructParams());
        foreach ($response as $currency => $price) {
            CurrencyPrice::query()
                ->whereHas('currency', function ($query) use ($currency) {
                    $query->where('code', '=', mb_strtoupper($currency));
                })
                ->update(['price' => $price['rub']]);
        }
    }

    private function constructParams(): array
    {
        $currencies = Currency::query()
            ->where('code', '!=', 'RUB')
            ->pluck('code')
            ->toArray();

        return [
            'ids' => implode(',', $currencies),
            'vs_currencies' => 'rub',
        ];
    }
}
