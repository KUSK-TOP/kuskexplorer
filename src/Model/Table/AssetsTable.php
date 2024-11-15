<?php
declare(strict_types=1);

namespace App\Model\Table;

//use Cake\ORM\Query;
use Cake\ORM\ResultSet;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\Validation\Validator;

use App\Model\Entity\Asset;

/**
 * Assets Model
 *
 * @method \App\Model\Entity\Asset newEmptyEntity()
 * @method \App\Model\Entity\Asset newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Asset[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Asset get($primaryKey, $options = [])
 * @method \App\Model\Entity\Asset findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Asset patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Asset[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Asset|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Asset saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Asset[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Asset[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Asset[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Asset[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AssetsTable extends Table
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

        $this->setTable('assets');
        $this->setDisplayField('asset_definition_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('asset')
            ->maxLength('asset', 65)
            ->requirePresence('asset', 'create')
            ->notEmptyString('asset')
            ->add('asset', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('asset_alias')
            ->maxLength('asset_alias', 100)
            ->allowEmptyString('asset_alias');

        $validator
            ->integer('block_height')
            ->requirePresence('block_height', 'create')
            ->notEmptyString('block_height');

        $validator
            ->scalar('transaction_hash')
            ->maxLength('transaction_hash', 65)
            ->requirePresence('transaction_hash', 'create')
            ->notEmptyString('transaction_hash');

        $validator
            ->integer('asset_definition_decimals')
            ->requirePresence('asset_definition_decimals', 'create')
            ->notEmptyString('asset_definition_decimals');

        $validator
            ->scalar('asset_definition_description')
            ->maxLength('asset_definition_description', 100)
            ->allowEmptyString('asset_definition_description');

        $validator
            ->scalar('asset_definition_name')
            ->maxLength('asset_definition_name', 100)
            ->requirePresence('asset_definition_name', 'create')
            ->notEmptyString('asset_definition_name');

        $validator
            ->scalar('asset_definition_symbol')
            ->maxLength('asset_definition_symbol', 100)
            ->requirePresence('asset_definition_symbol', 'create')
            ->notEmptyString('asset_definition_symbol');

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
        $rules->add($rules->isUnique(['asset']), ['errorField' => 'asset']);

        return $rules;
    }

    /**
     * Creates a new Asset
     * 
     * @param string $assetID The Asset ID
     * @param int $block The block where the asset was created
     * @param string $transaction The transactions where the asset was created
     * @param string $assetAlias The Asset Alias
     * @param string $assetDescription The Asset Description
     * @param int $assetDecimals The Asset Decimals
     * @param string $assetName The Asset Name
     * @param string $assetSymbol The Asset Symbol
     * @return bool
     */
    public function createAsset(
        string $assetID,
        string $assetAlias,
        int $block,
        string $transaction,
        string $assetDescription,
        int $assetDecimals,
        string $assetName,
        string $assetSymbol,
        bool $status
    ): bool {
        $asset = $this->newEmptyEntity();
        $asset->asset = $assetID;
        $asset->asset_alias = $assetAlias;
        $asset->block_height = $block;
        $asset->transaction_hash = $transaction;
        $asset->asset_definition_description = $assetDescription;
        $asset->asset_definition_decimals = $assetDecimals;
        $asset->asset_definition_name = $assetName;
        $asset->asset_definition_symbol = $assetSymbol;
        $asset->status = $status;

        $result = $this->save($asset);

        return $result !== false;
    }

    /**
     * Updates an existing Asset
     * 
     * @param string $assetID The Asset ID
     * @param int $block The block where the asset was created
     * @param string $transaction The transactions where the asset was created
     * @param string $assetAlias The Asset Alias
     * @param string $assetDescription The Asset Description
     * @param int $assetDecimals The Asset Decimals
     * @param string $assetName The Asset Name
     * @param string $assetSymbol The Asset Symbol
     * @return bool
     */
    public function updateAsset(
        string $assetID,
        string $assetAlias,
        int $block,
        string $transaction,
        string $assetDescription,
        int $assetDecimals,
        string $assetName,
        string $assetSymbol,
        bool $status
    ): bool {
        $asset = $this->getAsset($assetID);
        $asset->asset_alias = $assetAlias;
        $asset->block_height = $block;
        $asset->transaction_hash = $transaction;
        $asset->asset_definition_description = $assetDescription;
        $asset->asset_definition_decimals = $assetDecimals;
        $asset->asset_definition_name = $assetName;
        $asset->asset_definition_symbol = $assetSymbol;
        $asset->status = $status;

        $result = $this->save($asset);

        return $result !== false;
    }

    /**
     * Retrieve an Asset
     * 
     * @param string $asset
     * @return null|\Cake\ORM\Entity
     */
    public function getAsset(string $asset): null|Entity {
        
        $query = $this->find()
            ->where(['asset' => $asset]);
        $result = $query->first();
        if (is_null($result)) {
            debug($asset);
        }
        return $result;
    }

    /**
     * Retrieve all the Assets
     * 
     * @return null|\Cake\ORM\ResultSet
     */
    public function getAssets(): null|ResultSet {
        $query = $this->find();
        $result = $query->all();
        return $result;
    }
}
