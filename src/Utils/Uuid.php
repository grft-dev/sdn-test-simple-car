<?php

declare(strict_types=1);

namespace GrftTestSimpleCar\Utils;

/**
 * Helper class that produces RFC 4122 version 4 UUID strings.
 */
final class Uuid
{
    /**
     * Generates a new RFC 4122 version 4 UUID string.
     *
     * @return string A 36-character UUID string in the canonical 8-4-4-4-12 format.
     */
    public static function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return vsprintf(
            '%s%s-%s-%s-%s-%s%s%s',
            str_split(bin2hex($data), 4)
        );
    }
}
