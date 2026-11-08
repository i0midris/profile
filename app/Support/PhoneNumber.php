<?php

namespace App\Support;

/**
 * Normalizes phone numbers entered in the admin panel (e.g. "00967-777335118",
 * "777335118", "+967 777 335 118") into the formats links and schema need.
 */
class PhoneNumber
{
    public const DEFAULT_COUNTRY_CODE = '967';

    /**
     * International digits without "+" or leading "00", e.g. "967777335118".
     */
    public static function digits(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number) ?? '';

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            // Local format with trunk prefix, e.g. "0777335118".
            $digits = self::DEFAULT_COUNTRY_CODE . ltrim($digits, '0');
        } elseif (strlen($digits) === 9 && str_starts_with($digits, '7')) {
            // Yemeni mobile number without country code, e.g. "777335118".
            $digits = self::DEFAULT_COUNTRY_CODE . $digits;
        }

        return $digits;
    }

    /**
     * E.164 format, e.g. "+967777335118" (used in tel: links and Schema.org).
     */
    public static function e164(?string $number): ?string
    {
        $digits = self::digits($number);

        return $digits ? '+' . $digits : null;
    }

    public static function telUrl(?string $number): ?string
    {
        $e164 = self::e164($number);

        return $e164 ? 'tel:' . $e164 : null;
    }

    public static function whatsappUrl(?string $number): ?string
    {
        $digits = self::digits($number);

        return $digits ? 'https://wa.me/' . $digits : null;
    }

    /**
     * Human-readable format, e.g. "+967 777 335 118".
     */
    public static function display(?string $number): ?string
    {
        $digits = self::digits($number);

        if (!$digits) {
            return null;
        }

        if (str_starts_with($digits, self::DEFAULT_COUNTRY_CODE) && strlen($digits) === 12) {
            $local = substr($digits, 3);

            return '+' . self::DEFAULT_COUNTRY_CODE . ' ' . implode(' ', str_split($local, 3));
        }

        return '+' . $digits;
    }
}
