<?php

namespace Spatie\Activitylog\Handlers;

use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class EloquentHandler implements ActivitylogHandlerInterface
{
    /**
     * Log activity in an Eloquent model.
     *
     * @param string $text
     * @param string $causesactivity
     * @param string $model
     * @param array  $attributes
     *
     * @return bool
     * @internal param $userId
     */
    public function log($text, $causesactivity = '',  $model = '', $attributes = [])
    {
//        dd($causesactivity);

        list($causesactivity_id, $causesactivity_type) = $this->getCausesActivity($causesactivity, $model);

        Activity::create(
            [
                'text' => $text,
                'causes_activity_id' => $causesactivity_id,
                'causes_activity_type' => $causesactivity_type,
                'logs_activity_id' => ($model == '' ? null : $model->id),
                'logs_activity_type' => ($model == '' ? null : get_class($model)),

                'adjustments' => $attributes['adjustments'],
                'ip_address' => $attributes['ipAddress'],
            ]
        );

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
        $minimumDate = Carbon::now()->subMonths($maxAgeInMonths);
        Activity::where('created_at', '<=', $minimumDate)->delete();

        return true;
    }

    /**
     * @param $causesactivity
     * @param $model
     *
     * @return array
     */
    protected function getCausesActivity($causesactivity, $model)
    {
        if (!empty($causesactivity) && is_string($causesactivity)) {
            $causesactivity_id = !empty($model) ? $model->id : null;
            $causesactivity_type = $causesactivity;
            return array($causesactivity_id, $causesactivity_type);

        } else if (!empty($causesactivity)) {
            $causesactivity_id = $causesactivity->id;
            $causesactivity_type = get_class($causesactivity);
            return array($causesactivity_id, $causesactivity_type);
        } else {
            $causesactivity_id = (empty($model->causesActivity) ? null : $model->causesActivity->id);
            $causesactivity_type = (empty($model->causesActivity) ? null : get_class($model->causesActivity));
            return array($causesactivity_id, $causesactivity_type);
        }
    }
}
