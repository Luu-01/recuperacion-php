<?php
declare(strict_types=1);

namespace App\Core\Auth;

use App\Core\Model;
use App\Core\Contracts\Authenticatable as AuthenticatableContract;
use App\Core\Traits\Authenticatable as AuthenticatableTrait;

class AuthenticatableModel extends Model implements AuthenticatableContract
{
    use AuthenticatableTrait;
}