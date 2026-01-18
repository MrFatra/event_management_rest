<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payments = [
            [
                'registration_id' => 1,
                'order_id' => 'ORD-001-TEST',
                'amount' => 0,
                'status' => 'paid',
            ],
        ];

        foreach ($payments as $payment) {
            Payment::create($payment);
        }
    }
}
