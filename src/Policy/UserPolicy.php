<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Authorization\IdentityInterface;
use Authorization\Policy\BeforePolicyInterface;
use Cake\ORM\ResultSet;

/**
 * User policy
 */
class UserPolicy{
//    implements BeforePolicyInterface

//    public function before($user, $resource, $action)
//    {
//        if ($user->getPermissionslevel >= 2) {
//            return true;
//        }
//        // fall through
//    }

    /**
     * Check if $user can create User
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\User $resource
     * @return bool
     */
    public function canCreate(IdentityInterface $user, User $resource)
    {
    }

    /**
     * Check if $user can update User
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\User $resource
     * @return bool
     */
    public function canUpdate(IdentityInterface $user, User $resource)
    {
        if ($user->getPermissionsLevel() > $resource->getPermissionsLevel()){
            return true;
        }

        return $user->id == $resource->id;
    }

    /**
     * Check if $user can delete User
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\User $resource
     * @return bool
     */
    public function canDelete(IdentityInterface $user, User $resource)
    {
        $resourcePermissionsLvl = $resource->getPermissionsLevel();

        if ($resourcePermissionsLvl == 3){
            return false;
        }

        if ($user->getPermissionsLevel() > $resourcePermissionsLvl){
            return true;
        }

        return $user->id == $resource->id;
    }

    /**
     * Check if $user can view User
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\User $resource
     * @return bool
     */
    public function canView(IdentityInterface $user, User $resource)
    {
        if ($user->getPermissionsLevel() > $resource->getPermissionsLevel()){
            return true;
        }

        return $user->id == $resource->id;
    }

//    public function canIndex(IdentityInterface $user, User $users)
//    {
//        return $user->getPermissionsLevel() >= 2;
//    }

    public function canSetRole(IdentityInterface $user, User $resource){
        return $user->getPermissionsLevel() == 3;
    }



}
