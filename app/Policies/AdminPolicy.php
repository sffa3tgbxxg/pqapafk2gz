<?php

namespace App\Policies;

class AdminPolicy
{
    public function __invoke(): bool
    {
        return auth()->user()->isAdmin();
    }
}
