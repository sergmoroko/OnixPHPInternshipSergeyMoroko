<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Category;
use Authorization\IdentityInterface;

/**
 * Category policy
 */
class CategoryPolicy
{
    /**
     * Check if $user can create Category
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Category $category
     * @return bool
     */
    public function canCreate(IdentityInterface $user, Category $category)
    {
        return $user->getPermissionsLevel() >= 2;
    }

    /**
     * Check if $user can update Category
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Category $category
     * @return bool
     */
    public function canUpdate(IdentityInterface $user, Category $category)
    {
        return $user->getPermissionsLevel() >= 2;
    }

    /**
     * Check if $user can delete Category
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Category $category
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Category $category)
    {
        return $user->getPermissionsLevel() >= 2;
    }

    /**
     * Check if $user can view Category
     *
     * @param Authorization\IdentityInterface $user The user.
     * @param App\Model\Entity\Category $category
     * @return bool
     */
    public function canView(IdentityInterface $user, Category $category)
    {
    }
}
