<?php

namespace Spatie\Activitylog;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CausesActivity
{
    public function logsActivity() : MorphMany
    {
        return $this->morphMany($this->getMorphedClass(), 'causes_activity');
    }

    public function getMorphedClass()
    {
        return $this;
    }
}
