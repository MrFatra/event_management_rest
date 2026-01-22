<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Event;
use App\Models\EventRating;
use App\Models\Payment;
use App\Models\Registration;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('category')
            ->withAvg('ratings as average_ratings', 'rating')
            ->withCount('ratings')
            ->whereDate('start_date', '>=', Carbon::today());

        $upcoming = null;

        if (!$request->filled('event_type')) {
            $upcoming = (clone $query)
                ->orderBy('start_date', 'asc')
                ->first();

            $user = Auth::guard('sanctum')->user();

            $upcoming->is_registered = $user
                ? Registration::where('event_id', $upcoming->id)
                    ->where('user_id', $user->id)
                    ->exists()
                : false;

            $query->when(
                $upcoming,
                fn($q) => $q->where('id', '!=', $upcoming->id)
            );
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        $events = $query
            ->orderBy('start_date', 'asc')
            ->cursorPaginate(10);

        return ResponseHelper::genericSuccessResponse(
            'Event retrieved successfully',
            compact('upcoming', 'events')
        );
    }

    public function view($id)
    {
        try {
            $event = Event::with([
                'category',
                'speakers:id,name,bio,photo',
                'ratings.user:id,email,name'
            ])
                ->withAvg('ratings as average_ratings', 'rating')
                ->withCount('ratings')
                ->findOrFail($id);

            $user = Auth::guard('sanctum')->user();

            $event->rated_by_user = $user
                ? EventRating::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->exists()
                : false;

            $event->is_registered = $user
                ? Registration::where('event_id', $event->id)
                    ->where('status', 'registered')
                    ->where('user_id', $user->id)
                    ->exists()
                : false;

            // if ($event->is_registered) {
            $event->registration =
                $user ? Registration::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->with('payment')
                    ->first() : null;

            // $event->registration->makeHidden(['event_id', 'user_id']);
            // $event->registration->payment->makeHidden(['registration_id']);
            // }

            $event->payable = $user
                ? Registration::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->exists()
                : false;

            $event->makeHidden(['category_id', 'updated_at', 'created_at']);
            $event->speakers->makeHidden(['pivot']);
            $event->ratings->makeHidden(['event_id', 'user_id']);

            return ResponseHelper::genericSuccessResponse(
                'Detail event retrieved successfully',
                $event
            );
        } catch (ModelNotFoundException $th) {
            return ResponseHelper::genericDataNotFound($th);
        } catch (Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }

    public function register($id)
    {
        try {
            $user = Auth::user();

            $event = Event::findOrFail($id);

            $exists = Registration::where('user_id', $user->id)->where('event_id', $id)->exists();

            if ($exists)
                throw new Exception('You already registered on this event');

            $registration = Registration::create([
                'user_id' => $user->id,
                'event_id' => $id,
                'status' => $event->price <= 0 ? 'registered' : 'pending'
            ]);

            if ($registration->status == 'registered') {
                $orderCode = $registration->event->event_type->orderCode();
                $orderId = $orderCode . "-" . uniqid();

                Payment::create([
                    'registration_id' => $registration->id,
                    'order_id' => $orderId,
                    'gross_amount' => $registration->event->price,
                    'status' => 'paid'
                ]);
            }

            return ResponseHelper::genericSuccessResponse('Registration successful', $registration);
        } catch (ModelNotFoundException $th) {
            return ResponseHelper::genericDataNotFound($th);
        } catch (Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }

    public function rating(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string',
            ]);

            $user = $request->user();

            $exists = EventRating::where('user_id', $user->id)
                ->where('event_id', $id)
                ->exists();

            if ($exists) {
                return ResponseHelper::genericResponse(
                    false,
                    'You have already rated this event',
                    409
                );
            }

            $eventRating = EventRating::create([
                'user_id' => $user->id,
                'event_id' => $id,
                'rating' => $data['rating'],
                'comment' => $data['comment'],
            ]);

            return ResponseHelper::genericSuccessResponse('Event rated successfully', $eventRating);
        } catch (Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }

    public function unregister(Request $request, $id)
    {
        try {
            $user = $request->user();

            $registration = Registration::where('user_id', $user->id)
                ->where('event_id', $id)
                ->first();

            if ($registration->payment)
                $registration->payment->delete();

            if (!$registration)
                throw new Exception('Registration not found');

            $registration->delete();

            return ResponseHelper::genericSuccessResponse('Registration deleted successfully', $registration);
        } catch (Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }
}
