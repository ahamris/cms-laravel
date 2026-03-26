<?php

namespace App\Models;

use App\Models\Concerns\CachesSerializableRows;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperBaseModel
 */
class BaseModel extends Model
{
    use CachesSerializableRows;
}
