<?php

namespace App\Models\Abstract;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected $dateFormat = 'Y-m-d\TH:i:s.uP';
}
