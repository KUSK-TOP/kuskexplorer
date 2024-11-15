<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transfer Entity
 *
 * @property int $id
 * @property int $block_number
 * @property string $transaction_hash
 * @property string $from
 * @property string $to
 * @property int $amount
 * @property string $source_asset
 * @property string $destination_asset
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Transfer extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'block_number' => true,
        'transaction_hash' => true,
        'from' => true,
        'to' => true,
        'amount' => true,
        'source_asset' => true,
        'destination_asset' => true,
        'created' => true,
        'modified' => true,
    ];
}
