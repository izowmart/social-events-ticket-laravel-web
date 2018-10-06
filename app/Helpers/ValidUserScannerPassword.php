<?php
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 7/30/18
 * Time: 5:21 PM
 */

namespace App\Helpers;


use Illuminate\Contracts\Validation\Rule;

/*
 * Validate passwords for mobile phone users: User & Scanner
 * At least 6 characters with one upper and lowercase letter and a number
 *
 */

class ValidUserScannerPassword implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //check that the password contains at least 86characters, one uppercase and lowercase letter and a number
        if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/m", $value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be at least 6 characters with a lowercase and uppercase letter and a number';
    }
}