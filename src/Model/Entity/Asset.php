<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Asset Entity
 *
 * @property int $id
 * @property string $asset
 * @property int $block_height
 * @property string|null $asset_alias
 * @property string $transaction_hash
 * @property int $asset_definition_decimals
 * @property string|null $asset_definition_description
 * @property string $asset_definition_name
 * @property string $asset_definition_symbol
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Asset extends Entity
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
        'asset' => true,
        'asset_alias' => true,
        'block_height' => true,
        'transaction_hash' => true,
        'asset_definition_decimals' => true,
        'asset_definition_description' => true,
        'asset_definition_name' => true,
        'asset_definition_symbol' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
    ];
}
