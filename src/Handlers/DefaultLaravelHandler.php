<?php

namespace Spatie\Activitylog\Handlers;

use Log;

class DefaultLaravelHandler implements ActivitylogHandlerInterface
{
    /**
     * Log activity in Laravels log handler.
     *
     * @param string $text
     * @param string $model
     * @param array  $attributes
     * @return bool
     * @internal param $userId
     */
    public function log($text, $model = '',  $attributes = [])
    {
        $logText = $text;
        $logText .= (empty($model) ? ' (by '.$model->causesActivity->id.')' : '');
        $logText .= (count($attributes)) ? PHP_EOL.print_r($attributes, true) : '';

        Log::info($logText);

        return true;
    }

    /**
     * Clean old log records.
     *
     * @param int $maxAgeInMonths
     *
     * @return bool
     */
    public function cleanLog($maxAgeInMonths)
    {
        //this handler can't clean it's records

        return true;
    }
}
