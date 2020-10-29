<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Orders Model
 *
 * @property \App\Model\Table\ListingsTable&\Cake\ORM\Association\BelongsTo $Listings
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
// * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
// * @property \App\Model\Table\TransactionsTable&\Cake\ORM\Association\BelongsTo $Transactions
// * @property \App\Model\Table\TransactionsTable&\Cake\ORM\Association\BelongsTo $Transactions
 * @property \App\Model\Table\TransactionsTable&\Cake\ORM\Association\HasMany $Transactions
 *
 * @method \App\Model\Entity\Order newEmptyEntity()
 * @method \App\Model\Entity\Order newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Order[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Order get($primaryKey, $options = [])
 * @method \App\Model\Entity\Order findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Order patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Order[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Order|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Order saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrdersTable extends Table
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

        $this->setTable('orders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Listings', [
            'foreignKey' => 'listing_id',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'buyer_id',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'seller_id',
        ]);
        $this->belongsTo('Transactions', [
            'foreignKey' => 'seller_transaction_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Transactions', [
            'foreignKey' => 'buyer_transaction_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Transactions', [
            'foreignKey' => 'order_id',
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
            ->scalar('product_name')
            ->maxLength('product_name', 128)
            ->requirePresence('product_name', 'create')
            ->notEmptyString('product_name');

        $validator
            ->scalar('price')
            ->requirePresence('price', 'create')
            ->notEmptyString('price');

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
        $rules->add($rules->existsIn(['listing_id'], 'Listings'), ['errorField' => 'listing_id']);
        $rules->add($rules->existsIn(['buyer_id'], 'Users'), ['errorField' => 'buyer_id']);
        $rules->add($rules->existsIn(['seller_id'], 'Users'), ['errorField' => 'seller_id']);
        $rules->add($rules->existsIn(['seller_transaction_id'], 'Transactions'), ['errorField' => 'seller_transaction_id']);
        $rules->add($rules->existsIn(['buyer_transaction_id'], 'Transactions'), ['errorField' => 'buyer_transaction_id']);

        return $rules;
    }
}
