<?php
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 4/12/18
 * Time: 5:14 PM
 */

namespace App\Http\Traits;


use Exception;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\OptionsPriorities;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

trait SendFCMNotification
{
    public static function sendNotification($tokens, $data)
    {
        try {
            $optionsBuilder = new OptionsBuilder();
            $optionsBuilder->setPriority(OptionsPriorities::high);

            //iOS needs this
            $optionsBuilder->setContentAvailable(true);

            $options = $optionsBuilder->build();

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData($data);
            $data = $dataBuilder->build();

            $response = FCM::sendTo($tokens, $options, null, $data);

            logger("FCM response: \nsuccessful: ".$response->numberSuccess()
                ."\nfailed: ".$response->numberFailure()
                ."\nmodify: ".$response->numberModification()
                ."\n to delete: ".json_encode($response->tokensToDelete())
                ."\n to modify: ".json_encode($response->tokensToModify())
                ."\n to retry: ".json_encode($response->tokensToRetry())
                ."\n tokens with error: ".json_encode($response->tokensWithError())."\n"

            );

            if ($response->numberSuccess() > 0) {
                return 1;
            } else {
                return 0;
            }

        } catch (Exception $exception) {
            logger("notification error: " . $exception->getMessage() . "\n" . $exception->getTraceAsString());
            return response()->json([
                'error' => $exception->getMessage()
            ]);
        }

    }

}