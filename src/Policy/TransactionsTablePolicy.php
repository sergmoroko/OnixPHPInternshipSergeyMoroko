<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Table\TransactionsTable;
use Authorization\IdentityInterface;

/**
 * Transactions policy
 */
class TransactionsTablePolicy
{
    public function canIndex($user, $resource)
    {
        return $user->getPermissionsLevel() >= 2;
    }
}
