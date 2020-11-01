<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Listing Entity
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string|null $description
 * @property float $price
 * @property int $seller_id
 * @property int|null $buyer_id
 * @property string|null $images
 * @property string $status
 * @property \Cake\I18n\FrozenTime|null $sold_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Category $category
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Order[] $orders
 */
class Listing extends Entity
{

    const STATUS_LIST = ['active', 'sold', 'deleted'];

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
        'name' => true,
        'category_id' => true,
        'description' => true,
        'price' => true,
        'seller_id' => true,
        'buyer_id' => true,
        'images' => true,
        'status' => true,
        'sold_date' => true,
        'category' => true,
        'orders' => true,
    ];

    protected $_hidden = [
        'created' => true,
        'modified' => true,
        'user'
    ];
}
