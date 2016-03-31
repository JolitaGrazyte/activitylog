<?php

namespace Spatie\Activitylog\Test\Models;

use Spatie\Activitylog\CausesActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DummyUser2
 *
 * @package \Spatie\Activitylog\Test\Models
 */
class DummyUser2 extends Model
{
    use CausesActivity;

}
