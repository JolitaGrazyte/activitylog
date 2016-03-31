<?php

namespace Spatie\Activitylog\Handlers;

interface ActivitylogHandlerInterface
{
    /**
     * Log some activity.
     *
     * @param string $text
     * @param string $model
     * @param array  $attributes
     *
     * @return bool
     * @internal param string $user
     */
    public function log($text, $model = '', $attributes = []);

    /**
     * Clean old log records.
     *
     * @param int $maxAgeInMonths
     *
     * @return bool
     */
    public function cleanLog($maxAgeInMonths);
}
