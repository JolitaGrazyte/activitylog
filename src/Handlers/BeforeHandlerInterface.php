<?php

namespace Spatie\Activitylog\Handlers;

use Illuminate\Database\Eloquent\Model;

interface BeforeHandlerInterface
{
    /* Call to the log will only be made if this function returns true. */
    public function shouldLog(string $text, Model $model);
}
