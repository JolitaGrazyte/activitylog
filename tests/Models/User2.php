<?php

namespace Spatie\Activitylog\Test\Models;

use Spatie\Activitylog\CausesActivity;
use Illuminate\Database\Eloquent\Model;

class User2 extends Model
{
    use CausesActivity;
}
