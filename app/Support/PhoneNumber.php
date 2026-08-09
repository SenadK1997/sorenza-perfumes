<?php

namespace App\Support;

/**
 * Bosnian phone-number normalization.
 * All numbers are stored in canonical E.164 form: +387XXXXXXXXX
 *
 * Accepted inputs:
 *   +387603217297  → +387603217297
 *   00387603217297 → +387603217297
 *   387603217297   → +387603217297
 *   0603217297     → +387603217297   (BiH local, leading 0 → +387)
 *   060 321 7297   → +387603217297   (spaces stripped)
 *   060-321-7297   → +387603217297
 *   0038765/123-456 → +38765123456
 *
 * Non-BiH numbers already prefixed with a country code are kept as-is.
 */
class PhoneNumber
{
    public const BIH_CC = '387';

    /**
     * Return the canonical +CC... form, or null if input is empty/invalid.
     */
    public static function normalize(?string $raw): ?string
    {
        if ($raw === null) return null;
        $s = trim($raw);
        if ($s === '') return null;

        // Keep leading +, drop everything else non-digit
        $hasPlus = str_starts_with($s, '+');
        $digits  = preg_replace('/\D+/', '', $s) ?? '';

        if ($digits === '') return null;

        // 00 + country → +
        if (! $hasPlus && str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
            $hasPlus = true;
        }

        // Local BiH format starting with 0 → replace 0 with country code
        if (! $hasPlus && str_starts_with($digits, '0')) {
            $digits = self::BIH_CC . substr($digits, 1);
        }

        // Bare digits with BiH prefix (e.g. "387603...") → treat as country-coded
        // Bare digits without prefix and not starting with 0 → assume BiH
        if (! $hasPlus && ! str_starts_with($digits, self::BIH_CC)) {
            $digits = self::BIH_CC . $digits;
        }

        // Basic sanity: BiH mobile/fixed range 9-12 digits total after CC
        if (strlen($digits) < 8 || strlen($digits) > 15) {
            return null;
        }

        return '+' . $digits;
    }

    /**
     * Pretty display for BiH numbers: +387 60 321 7297
     * Falls back to normalized form for non-BiH.
     */
    public static function pretty(?string $raw): ?string
    {
        $norm = self::normalize($raw);
        if ($norm === null) return null;

        if (str_starts_with($norm, '+' . self::BIH_CC)) {
            $rest = substr($norm, 4); // digits after +387
            // Split like "60 321 7297" (2-3-4 or 3-3-4)
            $len = strlen($rest);
            if ($len === 8) {
                // e.g. 60 321 7 297 — do 2-3-3
                return '+' . self::BIH_CC . ' ' . substr($rest, 0, 2) . ' ' . substr($rest, 2, 3) . ' ' . substr($rest, 5);
            }
            if ($len === 9) {
                // e.g. 603 217 297 — do 2-3-4
                return '+' . self::BIH_CC . ' ' . substr($rest, 0, 2) . ' ' . substr($rest, 2, 3) . ' ' . substr($rest, 5);
            }
        }

        return $norm;
    }
}
