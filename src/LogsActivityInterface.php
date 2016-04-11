<?php

namespace Spatie\Activitylog;

interface LogsActivityInterface
{
    /* Get the message that needs to be logged for the given event. */
    public function getActivityDescriptionForEvent(string $eventName) : string;
}
