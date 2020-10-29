<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use Authorization\IdentityInterface;
use Cake\ORM\Query;

/**
 * Users policy
 */
class UsersTablePolicy
{
    public function scopeIndex($user, Query $query)
    {
        if ($user->getPermissionsLevel() >= 2) {
            return $query;
        }
        return null;
    }

    public function scopeView($user, Query $query){

    }



}
