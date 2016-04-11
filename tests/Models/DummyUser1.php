<?php

namespace Spatie\Activitylog\Test\Models;

use Spatie\Activitylog\CausesActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DummyUser1.
 */
class DummyUser1 extends Model
{
    use CausesActivity;
}
