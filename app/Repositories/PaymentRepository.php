<?php

namespace App\Repositories;

use App\Interfaces\PaymentRepoInterface;
use App\Models\Payment;

class PaymentRepository implements PaymentRepoInterface 
{
    public function createPayment(array $data)
    {
        return Payment::create($data);
    }
}
