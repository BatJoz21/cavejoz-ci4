<?php

namespace App\Validation;

class CustomRules
{
    public function strong_password(?string $str, ?string $fields = null, ?array $data = null, ?string &$error = null): bool
    {
        if(is_null($str)) {
            return false;
        }

        // Check if password is shorter than 8 characters
        if(mb_strlen($str) < 8) {
            $error = 'The {field} field must be at least 8 characters long.';
            return false;
        }

        // Check if password is longer than 16 characters
        if(mb_strlen($str) > 128) {
            $error = 'The {field} field must not be more than 16 characters long.';
            return false;
        }

        // Check if password has uppercase letter
        if(!preg_match('/[A-Z]/', $str)) {
            $error = 'The {field} field must contain at least one uppercase letter.';
            return false;
        }

        // Check if password has lowercase letter
        if(!preg_match('/[a-z]/', $str)) {
            $error = 'The {field} field must contain at least one lowercase letter.';
            return false;
        }

        // Check if password has number
        if(!preg_match('/[0-9]/', $str)) {
            $error = 'The {field} field must contain at least one number.';
            return false;
        }

        // Check if password has symbol
        if(!preg_match('/[\W_]/', $str)) {
            $error = 'The {field} field must contain at least one symbol.';
            return false;
        }

        return true;
    }
}
