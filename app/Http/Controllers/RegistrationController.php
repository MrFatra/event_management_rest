<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Registration;
use Auth;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $registrations = Registration::with(['event', 'payment'])
                ->where('user_id', $user->id);

            if ($request->filled('status')) {
                $registrations->where('status', $request->status);
            }

            $registrations = $registrations
                ->orderBy('created_at', 'desc')
                ->cursorPaginate(2);

            return ResponseHelper::genericSuccessResponse('Registration retrieved successfully', $registrations);
        } catch (\Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();

            $registration = Registration::with(['event', 'payment'])
                ->where('user_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();

            return ResponseHelper::genericSuccessResponse('Registration retrieved successfully', $registration);
        } catch (\Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }

    public function counts()
    {
        $user = Auth::guard('sanctum')->user();

        $pending = Registration::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $registered = Registration::where('user_id', $user->id)
            ->where('status', 'registered')
            ->count();

        $attended = Registration::where('user_id', $user->id)
            ->where('status', 'attended')
            ->count();

        return ResponseHelper::genericSuccessResponse('Count retrieved successfully', compact('pending', 'registered', 'attended'));
    }

    public function store(Request $request)
    {
        try {
            $user = $request->user();
            $eventId = $request->event_id;

            $event = \App\Models\Event::findOrFail($eventId);

            $exists = Registration::where('user_id', $user->id)->where('event_id', $eventId)->exists();
            if ($exists) {
                return ResponseHelper::genericResponse(false, 'You already registered on this event', 409);
            }

            $registration = Registration::create([
                'user_id' => $user->id,
                'event_id' => $eventId,
                'status' => $event->price <= 0 ? 'registered' : 'pending'
            ]);

            if ($event->price <= 0) {
                $orderCode = $event->event_type->orderCode();
                $orderId = $orderCode . "-" . uniqid();

                \App\Models\Payment::create([
                    'registration_id' => $registration->id,
                    'user_id' => $user->id,
                    'event_id' => $eventId,
                    'order_id' => $orderId,
                    'amount' => 0,
                    'status' => 'paid'
                ]);
            }

            return ResponseHelper::genericSuccessResponse('Registration successful', $registration);
        } catch (\Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }
}
