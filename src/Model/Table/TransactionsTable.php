<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Order;
use App\Model\Entity\Transaction;
use App\Utilities\Service;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\ServerRequest;
use Cake\I18n\FrozenTime;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transactions Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\OrdersTable&\Cake\ORM\Association\BelongsTo $Orders
 * @property \App\Model\Table\ComissionsTable&\Cake\ORM\Association\HasMany $Comissions
 *
 * @method \App\Model\Entity\Transaction newEmptyEntity()
 * @method \App\Model\Entity\Transaction newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Transaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Transaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\Transaction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Transaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Transaction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Transaction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transaction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @method searchManager()
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TransactionsTable extends Table
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

        $this->setTable('transactions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Orders', [
            'foreignKey' => 'order_id',
        ]);
        $this->hasMany('Comissions', [
            'foreignKey' => 'transaction_id',
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
            ->scalar('fee')
            ->allowEmptyString('fee');

        $validator
            ->scalar('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->scalar('type')
            ->requirePresence('type', 'create')
            ->inList('role', Transaction::TRANSACTION_TYPES)
            ->notEmptyString('type');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->existsIn(['order_id'], 'Orders'), ['errorField' => 'order_id']);

        return $rules;
    }

    public function createOrderTransactions(Order $order){
        // fee
        $feeAmount = Service::calculateFee($order->price);

        $feeTransaction = $this->newEmptyEntity();
        $feeTransaction->user_id = $this->Users->getOwner()->id;
        $feeTransaction->order_id = $order->id;
        $feeTransaction->amount = $feeAmount;
        $feeTransaction->type = 'comission';

        // buyer
        $buyerTransaction = $this->newEmptyEntity();
        $buyerTransaction->user_id = $order->buyer_id;
        $buyerTransaction->order_id = $order->id;
        $buyerTransaction->amount = -$order->price;
        $buyerTransaction->type = 'purchase';

        // seller
        $sellerTransaction = $this->newEmptyEntity();
        $sellerTransaction->user_id = $order->seller_id;
        $sellerTransaction->order_id = $order->id;
        $sellerTransaction->amount = $order->price - $feeAmount;
        $sellerTransaction->type = 'sale';
        $sellerTransaction->fee = $feeAmount;

        $this->saveMany([$sellerTransaction, $buyerTransaction, $feeTransaction]);

    }

    public function getById($id){
        if (!$this->exists(['id' => $id])){
            throw new RecordNotFoundException('There is no transaction with such id: '. $id);
        }
        return $this->get($id);
    }

    public function getTransactionsByUserId($id, ServerRequest $request){
        $query = ['user_id' => $id];
        if (isset($request->getQueryParams()['startDate'])) {
            $startDate = $request->getQueryParams()['startDate'];
            $query[] = ['created >=' => FrozenTime::createFromFormat('d-m-Y', $startDate)];
        }
        if (isset($request->getQueryParams()['endDate'])) {
            $endDate = $request->getQueryParams()['endDate'];
            $query[] = ['created <=' => FrozenTime::createFromFormat('d-m-Y', $endDate)];
        }

        return $this->find('all')
            ->where($query);
    }
}
