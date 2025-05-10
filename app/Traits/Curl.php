<?php

namespace App\Traits;


use App\Exceptions\ServerErrorException;
use App\Services\Logger;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

trait Curl
{
    /**
     * @throws ServerErrorException
     * @throws ConnectionException
     */
    private function curlGet(string $url, array $params = [], array $headers = []): array
    {
        $response = Http::withHeaders($headers)->get($url, $params);
        if ($response->successful()) {
            return $response->json();
        }

        Logger::error("Не удалось отправить GET запрос url: {$url}, params: " . json_encode($params) . ", headers: " . json_encode($params) . ". Ответ от сервера: " . json_encode($response->json()) . ")}");
        throw new ServerErrorException();
    }

    /**
     * @throws ServerErrorException
     * @throws ConnectionException
     */
    private function curlPost(string $url, array $params = [], array $headers = []): array
    {
        $response = Http::withHeaders($headers)->post($url, $params);
        if ($response->successful()) {
            return $response->json();
        }

        Logger::error("Не удалось отправить POST запрос url: {$url}, params: " . json_encode($params) . ", headers: " . json_encode($params) . ". Ответ от сервера: " . json_encode($response->json()) . ")}");
        throw new ServerErrorException();
    }
}
