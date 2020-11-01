<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Order;
use Authorization\IdentityInterface;

/**
 * Order policy
 */
class OrderPolicy
{

    /**
     * Check if $user can view Order
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Order $order
     * @return bool
     */
    public function canView(IdentityInterface $user, Order $order)
    {
        if ($user->getPermissionsLevel() >=2 || $user->id == $order->seller_id || $user->id === $order->buyer_id){
            return true;
        }

        return false;
    }
}
