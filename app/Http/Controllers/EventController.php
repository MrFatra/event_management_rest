<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('category')->select((new Event)->getFillable())->paginate(10);
        $events->getCollection()->makeHidden(['category_id']);

        return ResponseHelper::genericSuccessResponse('Event retrieved successfully', $events);
    }

    public function view($id)
    {
        try {
            $event = Event::with(['category', 'speakers:id,name,bio,photo', 'ratings.user:id,email,name' ])->findOrFail($id);

            $event->makeHidden(['category_id', 'updated_at', 'created_at']);

            $event->speakers->makeHidden(['pivot']);

            $event->ratings->makeHidden(['event_id', 'user_id']);

            return ResponseHelper::genericSuccessResponse('Detail event retrieved successfully', $event);
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

            if ($exists) throw new Exception('You already registered on this event');

            $registration = Registration::create([
                'user_id' => $user->id,
                'event_id' => $id,
                'status' => (float) $event->price <= 0 ? 'registered' : 'pending'
            ]);

            return ResponseHelper::genericSuccessResponse('Test Response', $registration);
        } catch (ModelNotFoundException $th) {
            return ResponseHelper::genericDataNotFound($th);
        } catch (Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }
}
