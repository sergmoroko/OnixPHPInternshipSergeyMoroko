<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Transaction;
use Authorization\IdentityInterface;

/**
 * Transaction policy
 */
class TransactionPolicy
{
    /**
     * Check if $user can view Transaction
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Transaction $transaction
     * @return bool
     */
    public function canView(IdentityInterface $user, Transaction $transaction)
    {
        if ($user->getPermissionsLevel() >=2 || $user->id == $transaction->user_id){
            return true;
        }

        return false;
    }
}
