<?php
declare(strict_types=1);

namespace App\Policy;

use Cake\ORM\Query;

/**
 * Users policy
 */
class UsersTablePolicy
{
    public function scopeIndex($user, $resource)
    {
        return $user->getPermissionsLevel() >= 2;
    }

}
