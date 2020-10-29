<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Table\UsersTable;
use Authorization\IdentityInterface as AuthorizationIdentity;
use Authentication\IdentityInterface as AuthenticationIdentity;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Authorization\AuthorizationServiceInterface;
use Authorization\Policy\ResultInterface;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * User Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property \Cake\I18n\FrozenDate $birth_date
 * @property float $balance
 * @property bool $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Role[] $roles
 * @property \App\Model\Entity\Transaction[] $transactions
 */
class User extends Entity implements AuthorizationIdentity, AuthenticationIdentity
{
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
        'first_name' => true,
        'last_name' => true,
        'password' => true,
        'birth_date' => true,
        'deleted' => true,
        'created' => true,
        'modified' => true,
        'roles' => true,
        'transactions' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'id',
        'password',
        'deleted',
        'roles',
        'created',
        'modified'
    ];

    protected function _setPassword($password) {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($password);
    }


    public function getRoleId($roleType){
        foreach ($this->roles as $role){
            if ($role->role == $roleType){
                return $role->id;
            }
        }
        return null;
    }

    public function getPermissionsLevel():int{
        $permissions = 1;
        foreach ($this->roles as $role){
            if ($role->role == 'admin' && $permissions < 2){
                $permissions = 2;
            }
            if ($role->role == 'owner'){
                $permissions = 3;
            }
        }
        return $permissions;
    }



    // IdentityInterface methods implementation
    public function getIdentifier()
    {
        return $this->id;
    }

    public function getOriginalData()
    {
        return $this;
    }

    public function setAuthorization(AuthorizationServiceInterface $service)
    {
        $this->authorization = $service;

        return $this;
    }


    public function can($action, $resource): bool
    {
        return $this->authorization->can($this, $action, $resource);
    }

    public function canResult(string $action, $resource): ResultInterface
    {
        return $this->authorization->canResult($this, $action, $resource);
    }

    public function applyScope(string $action, $resource)
    {
        return $this->authorization->applyScope($this, $action, $resource);
    }


    public function setRole($r){
        $rolesTable = TableRegistry::getTableLocator()->get('Roles');
        $role = $rolesTable->newEmptyEntity();
        $role->user_id = $this->id;
        $role->role = $r;

        $this->roles[] = $r;
    }
}
