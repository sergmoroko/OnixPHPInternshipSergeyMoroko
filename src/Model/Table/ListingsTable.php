<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Http\Exception\BadRequestException;
use App\Model\Entity\Listing;
use App\Model\Entity\Order;
use App\Utilities\Service;
use Authentication\IdentityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Listings Model
 *
 * @property \App\Model\Table\CategoriesTable&\Cake\ORM\Association\BelongsTo $Categories
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\OrdersTable&\Cake\ORM\Association\HasMany $Orders
 *
 * @method \App\Model\Entity\Listing newEmptyEntity()
 * @method \App\Model\Entity\Listing newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Listing[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Listing get($primaryKey, $options = [])
 * @method \App\Model\Entity\Listing findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Listing patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Listing[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Listing|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Listing saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Listing[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Listing[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Listing[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Listing[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ListingsTable extends Table
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

        $this->setTable('listings');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'seller_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'buyer_id',
        ]);
        $this->hasMany('Orders', [
            'foreignKey' => 'listing_id',
        ]);


        // Search config
        $this->addBehavior('Search.Search');

        // Setup search filter using search manager
        $this->searchManager()
            ->value('seller_id')
            ->value('buyer_id')
            ->value('status')
            ->add('q', 'Search.Like', [
                'before' => true,
                'after' => true,
                'fieldMode' => 'OR',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'fields' => ['name', 'Categories.name'],
            ])
            ->add('foo', 'Search.Callback', [
                'callback' => function (\Cake\ORM\Query $query, array $args, \Search\Model\Filter\Base $filter) {
                }
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
            ->scalar('name')
            ->maxLength('name', 128)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->numeric('price')
            ->requirePresence('price', 'create')
            ->notEmptyString('price');

        $validator
            ->scalar('images')
            ->allowEmptyFile('images');

        $validator
            ->scalar('status')
            ->notEmptyString('status');

        $validator
            ->dateTime('sold_date')
            ->allowEmptyDateTime('sold_date');

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
        $rules->add($rules->existsIn(['category_id'], 'Categories'), ['errorField' => 'category_id']);
        $rules->add($rules->existsIn(['seller_id'], 'Users'), ['errorField' => 'seller_id']);
        $rules->add($rules->existsIn(['buyer_id'], 'Users'), ['errorField' => 'buyer_id']);

        return $rules;
    }

    public function deleteListing($id)
    {
        $listing = $this->get($id);
        $status = $listing->status;
        if ($status != 'active') {
            throw new ForbiddenException('Can\'t delete listing with status "' . $status . '".');
        }
        $listing->status = 'deleted';
        if (!$this->save($listing)) {
            throw new BadRequestException($listing->getErrors());
        }
        return $listing;
    }

    public function buy(IdentityInterface $buyer, $id): ?Order
    {
        $listing = $this->getById($id);

        $this->validatePurchase($buyer, $listing);

        $this->getConnection()->transactional(function () use ($listing, $buyer) {

            $order = $this->Orders->createOrder($listing, $buyer->id, $listing->seller_id);
            $transactionsTable = TableRegistry::getTableLocator()->get('Transactions');
            $transactionsTable->createOrderTransactions($order);

            $feeAmount = Service::calculateFee($order->price);

            $owner = $this->Users->getOwner();
            $seller = $this->Users->get($listing->seller_id);

            $this->Users->updateBalance($listing->seller_id, $listing->price - $feeAmount);
            $this->Users->updateBalance($buyer->id, -$listing->price);
            $this->Users->updateBalance($owner->id, $feeAmount);

            $listing->status = 'sold';
            $listing->buyer_id = $buyer->id;
            $this->save($listing);

            $this->Users->saveMany([$seller, $buyer, $owner]);

            return $order;
        });
        return null;
    }

    private function validatePurchase(IdentityInterface $buyer, Listing $listing): void
    {
        if ($listing->status != 'active') {
            throw new ForbiddenException('Can\'t buy product with status "' . $listing->status . '".');
        }
        if ($listing->seller_id === $buyer->id) {
            throw new ForbiddenException('You can\'t buy your own product.');
        }
        if ($buyer->balance < $listing->price) {
            throw new BadRequestException(null, 'Not enough money to buy this product.');
        }
    }

    public function getById($id)
    {
        if (!$this->exists(['id' => $id])) {
            throw new RecordNotFoundException('There is no listing with such id: ' . $id);
        }
        return $this->get($id);
    }

    public function getListingsByUserId($id){
        return $this->find('all')->where(['seller_id' => $id]);
    }

}
