<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Http\Exception\BadRequestException;
use App\Model\Entity\User;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable&\Cake\ORM\Association\HasMany $Roles
 * @property \App\Model\Table\TransactionsTable&\Cake\ORM\Association\HasMany $Transactions
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Roles', [
            'foreignKey' => 'user_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('Transactions', [
            'foreignKey' => 'user_id',
        ]);

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 32)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 32)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('password')
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->date('birth_date')
            ->requirePresence('birth_date', 'create')
            ->notEmptyDate('birth_date');

        $validator
            ->decimal('balance')
            ->notEmptyString('balance');

        $validator
            ->boolean('deleted')
            ->notEmptyString('deleted');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }


    public function findForAuthentication(Query $query, array $options): Query
    {
        return $query->contain('Roles');
    }

    public function getUserById($id): EntityInterface
    {
        try {
            return $this->get($id, ['contain' => ['Roles']]);
        } catch (\Exception $e) {
            throw new RecordNotFoundException('There is no user with such id: ' . $id);
        }
    }

    public function updateBalance($id, float $amount)
    {
        $user = $this->get($id);
        $user->setAccess('balance', true);

        $user->balance += $amount;

        if (!$this->save($user)) {
            throw new BadRequestException($user->getErrors());
        }
        return $user;
    }


    public function addRole($id, $role)
    {
        $user = $this->get($id, ['contain' => ['Roles']]);
        if (!in_array($role, $user->roles)) {
            $r = $this->Roles->newEmptyEntity();
            $r->user = $id;
            $r->role = $role;
            $this->Roles->save($r);
        }
    }

    public function deleteRole($id, $role)
    {
        $user = $this->get($id, ['contain' => ['Roles']]);
        if (!in_array($role, $user->roles)) {
            $r = $this->Roles->newEmptyEntity();
            $r->user = $id;
            $r->role = $role;
            $this->Roles->save($r);
        }
    }

    public function getOwner(): User
    {
        return $this->get($this->Roles->find()->where(['role =' => 'owner'])->first()->user_id);
    }

    public function deleteById($id){
    $user = $this->getById($id);
    $user->deleted = true;
    $this->save($user);
    }


    public function getById($id)
    {
        if (!$this->exists(['id' => $id])) {
            throw new RecordNotFoundException('There is no user with such id: ' . $id);
        }
        return $this->get($id);
    }


}
