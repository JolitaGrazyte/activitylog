<?php

namespace Spatie\Activitylog\Handlers;

use Illuminate\Database\Eloquent\Model;
use Log;

class DefaultLaravelHandler implements ActivitylogHandlerInterface
{
    /* Log activity in Laravels log handler. */
    public function log(string $text, Model $model = null,  array $attributes = [])
    {
        $logText = $text;
        $logText .= (is_null($model) ?: ' (by '.$model->causes_activity_id.')');
        $logText .= (count($attributes)) ? PHP_EOL.print_r($attributes, true) : '';

        Log::info($logText);

        return true;
    }

    /* Clean old log records. */
    public function cleanLog(int $maxAgeInMonths) : bool
    {
        //this handler can't clean it's records
        return true;
    }
}
