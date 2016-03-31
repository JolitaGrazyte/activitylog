<?php

namespace Spatie\Activitylog;

use Illuminate\Contracts\Support;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        foreach (static::getRecordActivityEvents() as $eventName) {

            static::$eventName(function (LogsActivityInterface $model) use ($eventName) {

                $message = $model->getActivityDescriptionForEvent($eventName);

//                dd($eventName, $model);
                $causesActivity = $model->causesActivity ?? '';

                $adjustments = $eventName !== 'deleted' ? json_encode(array_intersect_key($model->fresh()->toArray(), $model->getDirty())) : '';

                if ($message != '') {
                    app(ActivitylogSupervisor::class)->log($message, $causesActivity, $model, $adjustments);
                }
            });
        }
    }

    public function causesActivity()
    {
        return $this->morphTo();
    }

    /**
     * Set the default events to be recorded if the $recordEvents
     * property does not exist on the model.
     *
     * @return array
     */
    protected static function getRecordActivityEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created', 'updated', 'deleting', 'deleted',
        ];
    }
}
