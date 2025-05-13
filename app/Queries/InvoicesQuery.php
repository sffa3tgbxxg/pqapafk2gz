<?php

namespace App\Queries;

use App\DTO\Invoices;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;

class InvoicesQuery
{
    private Invoices $filters;

    public function generate()
    {
        $query = Invoice::query();
        return $query
            ->with(['service', 'exchanger', 'user', 'currency'])
            ->when($this->filters->dateFrom, function (Builder $query) {
                $query->whereBetween('created_at', [$this->filters->dateFrom, $this->filters->dateTo]);
            })
            ->when($this->filters->user, function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->where('user_id', $this->filters->user);
                    $query->orWhere('user_nickname', $this->filters->user);
                });
            })
            ->when($this->filters->serviceId, function (Builder $query) {
                $query->where('service_id', $this->filters->serviceId);
            })
            ->when($this->filters->exchangerId, function (Builder $query) {
                $query->where('exchanger_id', $this->filters->exchangerId);
            })
            ->when($this->filters->invoiceId, function (Builder $query) {
                $query->where('id', $this->filters->invoiceId);
            })
            ->when($this->filters->externalInvoiceId, function (Builder $query) {
                $query->where('external_id', $this->filters->externalInvoiceId);
            })
            ->when($this->filters->status, function (Builder $query) {
                $query->where('status', $this->filters->status);
            })
            ->when($this->filters->problem, function (Builder $query) {
                $query->whereIn('status',[
                   Invoice::PROBLEM,
                   Invoice::KYC,
                   Invoice::CONSIDERATION,
                ]);
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);
    }

    /**
     * @param Invoices $filters
     * @return $this
     */
    public function setFilters(Invoices $filters): self
    {
        $this->filters = $filters;

        return $this;
    }
}
