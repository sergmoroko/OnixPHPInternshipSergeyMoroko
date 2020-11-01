<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Table\OrdersTable;
use Authorization\IdentityInterface;

/**
 * Orders policy
 */
class OrdersTablePolicy
{
    public function canIndex($user, $resource)
    {
        return $user->getPermissionsLevel() >= 2;
    }
}
