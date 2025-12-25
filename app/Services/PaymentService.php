<?php

namespace App\Services;

use App\Enums\EventType;
use App\Interfaces\PaymentRepoInterface;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Throwable;

class PaymentService
{
    protected $paymentRepository;

    public function __construct(PaymentRepoInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;

        Config::$clientKey = config('midtrans.client_key');
        Config::$serverKey = config('midtrans.server_key');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Process payment and generate snap token
     *
     * @param array $data
     * @return array
     * @throws Throwable
     */
    public function process(array $data): array
    {
        $registration = Registration::with([
            'event:id,event_type,price'
        ])
            ->where('id', $data['registration_id'])
            ->firstOrFail();

        $orderCode = $registration->event->event_type->orderCode();

        return DB::transaction(function () use ($data, $orderCode, $registration) {

            $orderId = $orderCode . '-' . uniqid();

            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => $registration->event->price,
                ],
                'customer_details' => [
                    'full_name' => $data['name'],
                    'email'      => $data['email'],
                ],
            ]);

            $payment = $this->paymentRepository->createPayment([
                'user_id'  => $data['user_id'],
                'registration_id' => $data['registration_id'],
                'order_id' => $orderId,
                'amount'   => $registration->event->price,
            ]);

            return [
                'payment'    => $payment,
                'snap_token' => $snapToken,
            ];
        });
    }
}
