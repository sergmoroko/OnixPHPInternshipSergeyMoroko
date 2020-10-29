<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up()
    {
        $this->table('categories')
            ->addColumn('parent_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addColumn('modified', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addIndex(
                [
                    'parent_id',
                ]
            )
            ->create();

        $this->table('comissions')
            ->addColumn('transaction_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('amount', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('created', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addColumn('modified', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addIndex(
                [
                    'transaction_id',
                ]
            )
            ->create();

        $this->table('listings')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => false,
            ])
            ->addColumn('category_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('seller_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('buyer_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('images', 'string', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('status', 'string', [
                'default' => 'active',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('sold_date', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addColumn('created', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addColumn('modified', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addColumn('price', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'buyer_id',
                ]
            )
            ->addIndex(
                [
                    'category_id',
                ]
            )
            ->addIndex(
                [
                    'seller_id',
                ]
            )
            ->create();

        $this->table('orders')
            ->addColumn('listing_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('product_name', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => false,
            ])
            ->addColumn('price', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('buyer_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('seller_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('seller_transaction_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('buyer_transaction_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('created', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addColumn('modified', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addIndex(
                [
                    'buyer_id',
                ]
            )
            ->addIndex(
                [
                    'buyer_transaction_id',
                ]
            )
            ->addIndex(
                [
                    'seller_id',
                ]
            )
            ->addIndex(
                [
                    'seller_transaction_id',
                ]
            )
            ->create();

        $this->table('roles', ['id' => false])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('role', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('transactions')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('fee', 'string', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('balance_before', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('balance_after', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addColumn('modified', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addColumn('order_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('amount', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('type', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('users')
            ->addColumn('first_name', 'string', [
                'default' => null,
                'limit' => 32,
                'null' => false,
            ])
            ->addColumn('last_name', 'string', [
                'default' => null,
                'limit' => 32,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('birth_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('balance', 'decimal', [
                'default' => '0.00',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('deleted', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addColumn('modified', 'timestampfractional', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'precision' => 6,
                'scale' => 6,
            ])
            ->addIndex(
                [
                    'email',
                ],
                ['unique' => true]
            )
            ->create();

        $this->table('categories')
            ->addForeignKey(
                'parent_id',
                'categories',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'CASCADE',
                ]
            )
            ->update();

        $this->table('comissions')
            ->addForeignKey(
                'transaction_id',
                'transactions',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'SET_NULL',
                ]
            )
            ->update();

        $this->table('listings')
            ->addForeignKey(
                'buyer_id',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION',
                ]
            )
            ->addForeignKey(
                'category_id',
                'categories',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'SET_NULL',
                ]
            )
            ->addForeignKey(
                'seller_id',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'CASCADE',
                ]
            )
            ->update();

        $this->table('orders')
            ->addForeignKey(
                'buyer_id',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'SET_NULL',
                ]
            )
            ->addForeignKey(
                'buyer_transaction_id',
                'transactions',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'SET_NULL',
                ]
            )
            ->addForeignKey(
                'seller_id',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'SET_NULL',
                ]
            )
            ->addForeignKey(
                'seller_transaction_id',
                'transactions',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'SET_NULL',
                ]
            )
            ->update();

        $this->table('roles')
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'CASCADE',
                ]
            )
            ->update();

        $this->table('transactions')
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'SET_NULL',
                ]
            )
            ->update();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down()
    {
        $this->table('categories')
            ->dropForeignKey(
                'parent_id'
            )->save();

        $this->table('comissions')
            ->dropForeignKey(
                'transaction_id'
            )->save();

        $this->table('listings')
            ->dropForeignKey(
                'buyer_id'
            )
            ->dropForeignKey(
                'category_id'
            )
            ->dropForeignKey(
                'seller_id'
            )->save();

        $this->table('orders')
            ->dropForeignKey(
                'buyer_id'
            )
            ->dropForeignKey(
                'buyer_transaction_id'
            )
            ->dropForeignKey(
                'seller_id'
            )
            ->dropForeignKey(
                'seller_transaction_id'
            )->save();

        $this->table('roles')
            ->dropForeignKey(
                'user_id'
            )->save();

        $this->table('transactions')
            ->dropForeignKey(
                'user_id'
            )->save();

        $this->table('categories')->drop()->save();
        $this->table('comissions')->drop()->save();
        $this->table('listings')->drop()->save();
        $this->table('orders')->drop()->save();
        $this->table('roles')->drop()->save();
        $this->table('transactions')->drop()->save();
        $this->table('users')->drop()->save();
    }
}
