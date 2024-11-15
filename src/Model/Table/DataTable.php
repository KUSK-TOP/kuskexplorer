<?php
declare(strict_types=1);

namespace App\Model\Table;

//use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Data Model
 *
 * @method \App\Model\Entity\Data newEmptyEntity()
 * @method \App\Model\Entity\Data newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Data[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Data get($primaryKey, $options = [])
 * @method \App\Model\Entity\Data findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Data patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Data[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Data|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Data saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Data[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Data[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Data[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Data[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DataTable extends Table
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

        $this->setTable('data');
        $this->setDisplayField('key');
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
            ->scalar('key')
            ->maxLength('key', 100)
            ->requirePresence('key', 'create')
            ->notEmptyString('key')
            ->add('key', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('value')
            ->maxLength('value', 255)
            ->allowEmptyString('value');

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
        $rules->add($rules->isUnique(['key']), ['errorField' => 'key']);

        return $rules;
    }

    /**
     * Returns the last processed block
     * 
     * @return int
     */
    public function getLastBlock(): int {
        $query = $this->find()
            ->select(['key', 'value'])
            ->where(['key'=>'last_block']);

        return intval($query->first()->value);
    }

    /**
     * Sets the last processed block
     * 
     * @param int $block The block number to set it to.
     * @return bool
     */
    public function setLastBlock(int $block): bool {
        $query = $this->find()
            ->select(['id', 'key', 'value'])
            ->where(['key'=>'last_block']);
        $lastBlock = $query->first();
        $lastBlock->value = $block;

        $result = $this->save($lastBlock);

        return $result !== false;
    }

    /**
     * Add to mined coins
     * 
     * @param int $amount The amount to add to the mined coins
     * @return bool
     */
    public function addMined(int $amount): bool {
        $query = $this->find()
            ->select(['id', 'key', 'value'])
            ->where(['key'=>'mined_coins']);
        $row = $query->first();
        $row->value += $amount;

        $result = $this->save($row);

        return $result !== false;
    }

    /**
     * Get mined coins
     * 
     * @return int
     */
    public function getMined(): int {
        $query = $this->find()
            ->select(['id', 'key', 'value'])
            ->where(['key'=>'mined_coins']);
        $row = $query->first();

        return intval($row->value);
    }
}
