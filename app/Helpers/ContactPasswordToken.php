<?php

namespace App\Helpers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class ContactPasswordToken
{
    public static function encode(int $contactId): string
    {
        return strtr(
            base64_encode(Crypt::encryptString((string) $contactId)),
            '+/=',
            '-_.'
        );
    }

    public static function decode(string $token): ?int
    {
        try {
            $decoded = base64_decode(strtr($token, '-_.', '+/='));
            $id = Crypt::decryptString($decoded);

            return (int) $id;
        } catch (DecryptException|\Throwable $e) {
            return null;
        }
    }

    public static function url(int $contactId): string
    {
        return route('contact.password.reset', ['token' => self::encode($contactId)]);
    }
}
