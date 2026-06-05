<?php

namespace App\Support;

class PhoneFormatter
{
    public static function indonesia(
        string $phone
    ): string {

        $phone = preg_replace(
            '/[^0-9]/',
            '',
            $phone
        );

        if (
            str_starts_with(
                $phone,
                '0'
            )
        ) {
            return '62' .
                substr($phone, 1);
        }

        return $phone;
    }
}