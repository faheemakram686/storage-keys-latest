<?php

namespace App\Services\Auth;

use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PasswordResetService
{
    public const TOKEN_EXPIRE_MINUTES = 60;

    public function findUserByEmail(string $email, string $modelClass): ?Model
    {
        return $modelClass::where('email', $email)->first();
    }

    public function findContactByEmail(string $email): ?Contact
    {
        return Contact::query()
            ->whereRaw('LOWER(email) = ?', [Str::lower($email)])
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->first();
    }

    public function createToken(string $email): string
    {
        DB::table('password_resets')->where('email', $email)->delete();

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        return $token;
    }

    public function findToken(string $email, string $token): ?object
    {
        return DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->first();
    }

    public function tokenIsValid(?object $token): bool
    {
        if (!$token) {
            return false;
        }

        return Carbon::parse($token->created_at)->diffInMinutes(Carbon::now()) < self::TOKEN_EXPIRE_MINUTES;
    }

    public function deleteToken(string $email): void
    {
        DB::table('password_resets')->where('email', $email)->delete();
    }
}
