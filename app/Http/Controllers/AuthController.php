<?php

namespace App\Http\Controllers;

use App\Helpers\GenerateToken;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

            $user = User::where('email', $data['email'])->first();

            if (!$user) {
                throw new Exception('Wrong username/password');
            }

            $verifiedPass = Hash::check($data['password'], $user->password);

            if (!$verifiedPass) {
                throw new Exception('Wrong username/password');
            }

            $token = GenerateToken::bearer($user, 'auth')->plainTextToken;

            return ResponseHelper::genericSuccessResponse('Login Successful.', compact(['user', 'token']));
        } catch (ValidationException $th) {
            return ResponseHelper::genericValidationException($th);
        } catch (Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }

    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'name' => 'required',
                'password' => 'required|min:6'
            ]);

            $user = User::where('email', $data['email'])->first();

            if ($user)
                throw new Exception('User already exist');

            $user = User::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => $data['password']
            ]);

            return ResponseHelper::genericSuccessResponse('User registered successfully', $user);
        } catch (ValidationException $th) {
            return ResponseHelper::genericValidationException($th);
        } catch (Exception $ex) {
            return ResponseHelper::genericException($ex);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user || !$user->currentAccessToken()) {
                return ResponseHelper::genericResponse(
                    false,
                    'Unauthenticated',
                    401
                );
            }

            $user->currentAccessToken()->delete();

            return ResponseHelper::genericSuccessResponse(
                'Logout Successful',
                $user,
            );
        } catch (Exception $e) {
            return ResponseHelper::genericException($e);
        }
    }

}
