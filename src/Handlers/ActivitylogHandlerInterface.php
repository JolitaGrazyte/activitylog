<?php

namespace Spatie\Activitylog\Handlers;

use Illuminate\Database\Eloquent\Model;

interface ActivitylogHandlerInterface
{
    /* Log some activity. */
    public function log(string $text, Model $model = null, array $attributes = []) : bool;

    /* Clean old log records. */
    public function cleanLog(int $maxAgeInMonths) : bool;
}
