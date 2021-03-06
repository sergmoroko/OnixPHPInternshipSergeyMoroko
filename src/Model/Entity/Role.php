<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Role Entity
 *
 * @property int $user_id
 * @property string $role
 * @property int $id
 *
 * @property \App\Model\Entity\User $user
 */
class Role extends Entity
{
    const ROLES_LIST = ['user', 'admin', 'owner'];

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'role' => true,
        'id' => true,
        'user' => true,
    ];
}
