<?php

namespace App\Queries\Statistics;

use App\Models\Invoice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExchangersQuery
{
    public function generate(array $data): Collection
    {
        return Invoice::query()
            ->select(
                DB::raw(
                    'SUM()'
                )
            )
            ->where('service_id', $data['service_id'])
            ->where('exchanger_id', $data['exchanger_id'])
            ->get();
    }


}
