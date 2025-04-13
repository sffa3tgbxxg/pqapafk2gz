<?php

namespace App\Traits;


use Illuminate\Support\Facades\Http;

trait Curl
{
    private function curlGet(string $url, array $params = null): array
    {
        return Http::get($url, $params)->json();
    }
}
