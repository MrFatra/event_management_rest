<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function tickets()
    {
        $registrations = Registration::with(['event', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $user = Auth::user();

        return view('profile.tickets', compact('registrations', 'user'));
    }

    public function registerForEvent($id)
    {
        $user = Auth::user();
        $event = \App\Models\Event::findOrFail($id);

        $exists = Registration::where('user_id', $user->id)->where('event_id', $id)->exists();
        if ($exists) {
            return back()->with('error', 'Anda sudah terdaftar di event ini.');
        }

        $registration = Registration::create([
            'user_id' => $user->id,
            'event_id' => $id,
            'status' => $event->price > 0 ? 'pending' : 'registered'
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

        if ($event->price > 0) {
            return redirect()->route('payments.pay', $registration->id);
        }

        return redirect()->route('profile.tickets')->with('success', 'Pendaftaran berhasil! Silakan cek riwayat tiket Anda.');
    }

    public function cancelRegistration($id)
    {
        try {

            $registration = Registration::findOrFail($id);

            if (!$registration) {
                return back()->with('error', 'Anda tidak terdaftar di event ini.');
            }

            if ($registration->payment) {
                $registration->payment->delete();
            }

            $registration->delete();

            return redirect()->route('profile.tickets')->with('success', 'Pendaftaran berhasil dibatalkan.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal membatalkan pendaftaran.');
        }
    }
}
