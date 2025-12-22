<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);

        try {
            $user = User::where('email', $data['email'])->firstOrFail();

            $verifiedPass = Hash::check($data['password'], $user->password);

            if (!$verifiedPass) {
                throw new Exception('Wrong username/password');
            }

            return ResponseHelper::genericSuccessResponse('Login Successful.', $user);
        } catch (ModelNotFoundException $th) {
            return ResponseHelper::genericDataNotFound($th);
        } catch (Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }

    public function register(Request $request) {
        $data = $request->only(['email', 'name', 'password']);

        try {
            $user = User::where('email', $data['email'])->first();

            if ($user) throw new Exception('User already exist');

            $user = User::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => $data['password']
            ]);

            return ResponseHelper::genericSuccessResponse('User registered successfully', $user);
        } catch (\Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }
}
