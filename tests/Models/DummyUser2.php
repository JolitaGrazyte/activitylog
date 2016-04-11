<?php

namespace Spatie\Activitylog\Test\Models;

use Spatie\Activitylog\CausesActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DummyUser2.
 */
class DummyUser2 extends Model
{
    use CausesActivity;
}
