<?php

namespace Spatie\Activitylog\Handlers;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class EloquentHandler implements ActivitylogHandlerInterface
{
    /* Log activity in an Eloquent model. */
    public function log(string $text, Model $model = null, array $attributes = []) : bool
    {
        Activity::create(
            [
                'text' => $text,
                'causes_activity_id' => (is_null($model) ?: $model->causes_activity_id),
                'causes_activity_type' => (is_null($model) ?: $model->causes_activity_type),
                'logs_activity_id' => (is_null($model) ?: $model->id),
                'logs_activity_type' => (is_null($model) ?: get_class($model)),

                'adjustments' => $attributes['adjustments'],
                'ip_address' => $attributes['ipAddress'],
            ]
        );

        return true;
    }

    /* Clean old log records. */
    public function cleanLog(int $maxAgeInMonths) : bool
    {
        $minimumDate = Carbon::now()->subMonths($maxAgeInMonths);
        Activity::where('created_at', '<=', $minimumDate)->delete();

        return true;
    }
}
