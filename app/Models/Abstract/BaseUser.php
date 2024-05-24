<?php

namespace App\Models\Abstract;

use Illuminate\Foundation\Auth\User;

abstract class BaseUser extends User
{
    protected $dateFormat = 'Y-m-d\TH:i:s.uP';
}
