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

    public static function formatPhoneNumber($phone_number)
    {
        if(starts_with($phone_number, "7")){
            return "254".$phone_number;
        }elseif (starts_with($phone_number,"07")){
            return "254" . substr($phone_number, 1);
        } elseif (starts_with($phone_number,"+2547")){
            return substr($phone_number,1);
        }elseif (starts_with($phone_number,"2547")){
            return $phone_number;
        }

    }
}