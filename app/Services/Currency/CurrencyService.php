<?php

namespace App\Services\Currency;

use App\Exceptions\ServerErrorException;
use App\Models\Currency;
use App\Models\CurrencyPrice;
use App\Traits\Curl;
use Illuminate\Http\Client\ConnectionException;

class CurrencyService
{
    use Curl;

    private string $url;

    public function __construct()
    {
        $this->url = config('keys.coingecko_api_url') . "/api/v3/simple/price";;
    }

    /**
     * @throws ServerErrorException
     * @throws ConnectionException
     */
    public function updateCurrencies(): void
    {
        $response = $this->curlGet($this->url, $this->constructParams(), ['Content-Type' => 'application/json']);
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

    /**
     * @param string $needCurrencyName
     * @param float $amount
     * @return array (convert amount, price)
     */
    public function convertAmount(string $needCurrencyName, float $amount): array
    {
        $price = CurrencyPrice::query()
            ->whereHas('currency', function ($query) use ($needCurrencyName) {
                $query->where('code', '=', mb_strtoupper($needCurrencyName));
            })
            ->first()
            ?->price ?? 0;

        return [$amount / $price, $price];
    }
}
