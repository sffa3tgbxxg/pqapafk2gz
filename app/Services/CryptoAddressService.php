<?php

namespace App\Services;

use App\Exceptions\ServerErrorException;
use App\Models\CryptoAddress;
use App\Models\Currency;
use BitcoinPHP\BitcoinECDSA\BitcoinECDSA;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class CryptoAddressService
{
    public function generate(string $currencyCode): CryptoAddress
    {
        $currencyId = Currency::getIdByCode($currencyCode);

        try {
            $methodName = "generate{$currencyCode}";
            [$mnemonic, $cryptoAddress] = $this->$methodName();

            DB::beginTransaction();
            $address = CryptoAddress::query()->create(['address' => $cryptoAddress, 'mnemonic' => $mnemonic, 'currency_id' => $currencyId, 'balance' => 0]);
            DB::commit();
            return $address;
        } catch (\Throwable $exception) {
            DB::rollBack();
            Logger::error("Не удалось сгенерировать крипто-адрес", [
                'currency_id' => $currencyId,
                'currency_code' => $currencyCode,
                'message' => $exception->getMessage(),
            ]);
            throw new ServerErrorException();
        }
    }

    public function generateBITCOIN(): array
    {
        $process = new Process([config('app.python_exec'), base_path('/python/generate_bitcoin_address.py')]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \Exception('Python script failed: ' . $process->getErrorOutput());
        }
        [$mnemonic, $address] = explode("\n", trim($process->getOutput()));
        return [$mnemonic, $address];
    }

    public function updateBalance(int $id, float $amount): void
    {
        CryptoAddress::query()->where('id', $id)->update(['balance' => $amount]);
    }
}
