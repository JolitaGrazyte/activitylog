<?php

namespace Spatie\Activitylog;

use Illuminate\Database\Eloquent\Relations\MorphTo;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        collect(static::getRecordActivityEvents())->map(function ($eventName) {

            return static::$eventName(function (LogsActivityInterface $model) use ($eventName) {

                $message = $model->getActivityDescriptionForEvent($eventName);

                $adjustments = $eventName !== 'deleted' ? json_encode(array_intersect_key($model->fresh()->toArray(), $model->getDirty())) : '';

                if ($message != '') {
                    app(ActivitylogSupervisor::class)->log($message, $model, $adjustments);
                }
            });

        });
    }

    public function causesActivity() : MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Set the default events to be recorded if the $recordEvents
     * property does not exist on the model.
     *
     * @return array
     */
    protected static function getRecordActivityEvents() : array
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created', 'updated', 'deleting', 'deleted',
        ];
    }
}
