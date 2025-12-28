<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $registrations = Registration::with('event')
                ->where('user_id', $user->id);

            if ($request->filled('status')) {
                $registrations->where('status', $request->status);
            }

            $registrations = $registrations->paginate(10);

            return ResponseHelper::genericSuccessResponse('Registration retrieved successfully', $registrations);
        } catch (\Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }
}
