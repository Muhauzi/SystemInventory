<?php

namespace App\Repositories;

use App\Models\TagihanKerusakan;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function createTransaction(array $data)
    {
        return TagihanKerusakan::create($data);
    }
}
