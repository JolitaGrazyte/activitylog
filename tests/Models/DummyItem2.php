<?php

namespace Spatie\Activitylog\Test\Models;

use Spatie\Activitylog\LogsActivityInterface;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogsActivity;

/**
 * Class DummyItem2.
 */
class DummyItem2 extends Model implements LogsActivityInterface
{
    use LogsActivity;

    protected $guarded = [];

    public function getActivityDescriptionForEvent(string $eventName) : string
    {
        if ($eventName == 'created') {
            return 'Item "'.$this->name.'" was created';
        }

        if ($eventName == 'updated') {
            return 'Item "'.$this->name.'" was updated';
        }

        if ($eventName == 'deleted') {
            return 'Item "'.$this->name.'" was deleted';
        }

        return '';
    }
}
