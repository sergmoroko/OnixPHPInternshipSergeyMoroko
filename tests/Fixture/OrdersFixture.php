<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrdersFixture
 */
class OrdersFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'listing_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'product_name' => ['type' => 'string', 'length' => 128, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null],
        'price' => ['type' => 'string', 'length' => null, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null],
        'buyer_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'seller_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'seller_transaction_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'buyer_transaction_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'created' => ['type' => 'timestampfractional', 'length' => null, 'default' => null, 'null' => false, 'comment' => null, 'precision' => 6],
        'modified' => ['type' => 'timestampfractional', 'length' => null, 'default' => null, 'null' => false, 'comment' => null, 'precision' => 6],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'orders_buyer_id_fkey' => ['type' => 'foreign', 'columns' => ['buyer_id'], 'references' => ['users', 'id'], 'update' => 'noAction', 'delete' => 'setNull', 'length' => []],
            'orders_buyer_transaction_id_fkey' => ['type' => 'foreign', 'columns' => ['buyer_transaction_id'], 'references' => ['transactions', 'id'], 'update' => 'noAction', 'delete' => 'setNull', 'length' => []],
            'orders_listing_id_fkey' => ['type' => 'foreign', 'columns' => ['listing_id'], 'references' => ['listings', 'id'], 'update' => 'noAction', 'delete' => 'setNull', 'length' => []],
            'orders_seller_id_fkey' => ['type' => 'foreign', 'columns' => ['seller_id'], 'references' => ['users', 'id'], 'update' => 'noAction', 'delete' => 'setNull', 'length' => []],
            'orders_seller_transaction_id_fkey' => ['type' => 'foreign', 'columns' => ['seller_transaction_id'], 'references' => ['transactions', 'id'], 'update' => 'noAction', 'delete' => 'setNull', 'length' => []],
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'listing_id' => 1,
                'product_name' => 'Lorem ipsum dolor sit amet',
                'price' => 'Lorem ipsum dolor sit amet',
                'buyer_id' => 1,
                'seller_id' => 1,
                'seller_transaction_id' => 1,
                'buyer_transaction_id' => 1,
                'created' => 1601832915,
                'modified' => 1601832915,
            ],
        ];
        parent::init();
    }
}
