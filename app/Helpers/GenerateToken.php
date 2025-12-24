<?php

namespace App\Helpers;

use App\Models\User;

class GenerateToken {
    public static function bearer(User $user, string $name) {
        return $user->createToken($name, expiresAt: \Carbon\Carbon::now()->addMinutes((float) env('BEARER_TOKEN_EXPIRES_AT', 4320)));
    }
}