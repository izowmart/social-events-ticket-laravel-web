<?php
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 9/6/18
 * Time: 1:24 PM
 */

namespace App\Http\Traits;


trait UniversalMethods
{
    public static function getValidationErrorsAsString($errorArray)
    {
        $errorArrayTemp = [];
        $error_strings = "";
        foreach ($errorArray as $index => $item) {
            $errStr = $item[0];
            array_push($errorArrayTemp, $errStr);
        }
        if (!empty($errorArrayTemp)) {
            $error_strings = implode('. ', $errorArrayTemp);
        }

        return $error_strings;
    }



}