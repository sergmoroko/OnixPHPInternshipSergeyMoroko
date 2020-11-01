<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Listing;
use Authorization\IdentityInterface;
use Cake\Http\Exception\ForbiddenException;

/**
 * Listing policy
 */
class ListingPolicy
{

    /**
     * Check if $user can update Listing
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Listing $listing
     * @return bool
     */
    public function canUpdate(IdentityInterface $user, Listing $listing)
    {
        if ($user->getPermissionsLevel() >=2 || $user->id == $listing->seller_id){
            return true;
        }

        return false;
    }

    /**
     * Check if $user can delete Listing
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Listing $listing
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Listing $listing)
    {

        if ($user->getPermissionsLevel() >=2 || $user->id == $listing->seller_id){
            return true;
        }

        return false;
    }

}
