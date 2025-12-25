<?php

namespace App\Interfaces;

interface PaymentRepoInterface {
    public function createPayment(array $data);
}