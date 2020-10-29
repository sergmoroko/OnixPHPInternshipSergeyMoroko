<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Order Entity
 *
 * @property int $id
 * @property int|null $listing_id
 * @property string $product_name
 * @property string $price
 * @property int|null $buyer_id
 * @property int|null $seller_id
 * @property int $seller_transaction_id
 * @property int $buyer_transaction_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Listing $listing
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Transaction[] $transactions
 */
class Order extends Entity
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
        'listing_id' => true,
        'product_name' => true,
        'price' => true,
        'buyer_id' => true,
        'seller_id' => true,
        'seller_transaction_id' => true,
        'buyer_transaction_id' => true,
        'created' => true,
        'modified' => true,
        'listing' => true,
        'user' => true,
        'transactions' => true,
    ];
}
