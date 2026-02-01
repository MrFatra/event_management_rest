<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('event_type', $request->type);
        }

        $events = $query->latest()->paginate(9);
        $categories = Category::all();

        return view('events.index', compact('events', 'categories'));
    }

    public function show($id)
    {
        $event = Event::with(['category', 'speakers', 'registrations', 'payments'])->findOrFail($id);

        $registration = $event->registrations()->where('user_id', auth()->id())->first();
        $payment = $registration?->payment;

        return view('events.show', compact('event', 'registration', 'payment'));
    }
}
