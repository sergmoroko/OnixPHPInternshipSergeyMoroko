<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Listing;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Orders Model
 *
 * @property \App\Model\Table\ListingsTable&\Cake\ORM\Association\BelongsTo $Listings
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
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
 * @method searchManager()
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
        $this->hasMany('Transactions', [
            'foreignKey' => 'order_id',
        ]);

        // Search config
        $this->addBehavior('Search.Search');

        // Setup search filter using search manager
        $this->searchManager()
            ->add('foo', 'Search.Callback', [
                'callback' => function (\Cake\ORM\Query $query, array $args, \Search\Model\Filter\Base $filter) {
                }
            ]
    );
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
            ->decimal('price')
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

        return $rules;
    }

    public function createOrder(Listing $listing, $buyer_id, $seller_id){
        $order = $this->newEmptyEntity();
        $order->price = $listing->price;
        $order->product_name = $listing->name;
        $order->buyer_id = $buyer_id;
        $order->seller_id = $seller_id;
        $order->listing_id = $listing->id;

        $this->save($order);

        return $order;
    }

    public function getById($id){
        if (!$this->exists(['id' => $id])){
            throw new RecordNotFoundException('There is no order with such id: '. $id);
        }
        return $this->get($id);
    }

    public function getUserOrders($user){
        $this->find('all')->where(['seller_id' => $user->id, 'buyer_id' => $user->id]);
    }
}
