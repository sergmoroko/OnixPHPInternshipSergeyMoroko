<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transaction Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $fee
 * @property string $balance_before
 * @property string $balance_after
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int|null $order_id
 * @property string $amount
 * @property string $type
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Order $order
 * @property \App\Model\Entity\Comission[] $comissions
 */
class Transaction extends Entity
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
        'user_id' => true,
        'fee' => true,
        'balance_before' => true,
        'balance_after' => true,
        'created' => true,
        'modified' => true,
        'order_id' => true,
        'amount' => true,
        'type' => true,
        'user' => true,
        'order' => true,
        'comissions' => true,
    ];
}
