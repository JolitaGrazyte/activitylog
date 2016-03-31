<?php

namespace Spatie\Activitylog;

trait CausesActivity
{

    public function logsActivity()
    {
        return $this->morphMany($this->getMorphedClass(), 'causes_activity');
    }

    public function getMorphedClass()
    {
        return $this;
    }

}
