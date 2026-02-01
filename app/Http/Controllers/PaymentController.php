<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Payment;
use App\Services\PaymentService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Midtrans\Notification;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function checkout(Request $request)
    {
        try {
            $data = [
                'user_id' => $request->user()->id,
                'registration_id' => $request->registration_id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ];

            $service = $this->paymentService->process($data);

            return ResponseHelper::genericSuccessResponse('Payment completed', $service);
        } catch (ModelNotFoundException $th) {
            return ResponseHelper::genericDataNotFound($th);
        } catch (\Exception $th) {
            return ResponseHelper::genericException($th);
        }
    }

    public function show($orderId)
    {
        try {
            $payment = Payment::where('order_id', $orderId)->firstOrFail();

            return ResponseHelper::genericSuccessResponse('Payment retrieved successfully', $payment);
        } catch (\Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }

    public function webhook(Request $request)
    {
        try {
            $serverKey = config('midtrans.server_key');

            $signatureKey = hash(
                "sha512",
                $request->order_id .
                $request->status_code .
                $request->gross_amount .
                $serverKey
            );

            if ($signatureKey !== $request->signature_key) {
                return ResponseHelper::genericResponse(false, 'Invalid signature key', statusCode: 403);
            }

            $payment = Payment::with('registration')->where('order_id', $request->order_id)->firstOrFail();

            if (in_array($request->transaction_status, ['settlement', 'capture'])) {
                $payment->update(['status' => 'paid']);
                $payment->registration->update(['status' => 'registered']);
            } elseif (in_array($request->transaction_status, ['cancel', 'expire'])) {
                $payment->update(['status' => 'failed']);
                $payment->registration->update(['status' => 'cancelled']);
            }

            return ResponseHelper::genericSuccessResponse('Webhook payment retrieved', $request->all());
        } catch (ModelNotFoundException $th) {
            return ResponseHelper::genericDataNotFound($th);
        } catch (\Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }

    public function pay(Request $request, $registrationId)
    {
        try {
            $data = [
                'user_id' => $request->user()->id,
                'registration_id' => $registrationId,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ];

            $paymentData = $this->paymentService->process($data);
            $snapToken = $paymentData['snap_token'];
            $payment = $paymentData['payment'];

            return view('payments.pay', compact('snapToken', 'payment'));
        } catch (\Exception $ex) {
            return redirect()->route('profile.tickets')->with('error', $ex->getMessage());
        }
    }
}
