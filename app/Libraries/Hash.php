<?php

namespace App\Libraries;

/**
 * Implement library for password treatment.
 */
class Hash
{
    /**
     * Make hashed password.
     * 
     * @param string $value Plane text.
     * @return string $hashedPassword
     */
    public static function make(string $value): string
    {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    /**
     * Check value matches with hashed value.
     * 
     * @param string $value Plane text.
     * @param string $hashedValue.
     * @return bool
     */
    public static function check(string $value, string $hashedValue): bool
    {
        return password_verify($value, $hashedValue);
    }
}