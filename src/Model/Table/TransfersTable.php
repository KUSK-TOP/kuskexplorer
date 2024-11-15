<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\ResultSet;
//use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transfers Model
 *
 * @method \App\Model\Entity\Transfer newEmptyEntity()
 * @method \App\Model\Entity\Transfer newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Transfer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Transfer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Transfer findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Transfer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Transfer[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Transfer|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transfer saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transfer[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Transfer[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Transfer[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Transfer[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TransfersTable extends Table
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

        $this->setTable('transfers');
        $this->setDisplayField('transaction_hash');
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
            ->requirePresence('block_number', 'create')
            ->notEmptyString('block_number');

        $validator
            ->scalar('transaction_hash')
            ->maxLength('transaction_hash', 65)
            ->requirePresence('transaction_hash', 'create')
            ->notEmptyString('transaction_hash');

        $validator
            ->scalar('from')
            ->maxLength('from', 50)
            ->requirePresence('from', 'create')
            ->notEmptyString('from');

        $validator
            ->scalar('to')
            ->maxLength('to', 50)
            ->requirePresence('to', 'create')
            ->notEmptyString('to');

        $validator
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->scalar('source_asset')
            ->maxLength('source_asset', 65)
            ->requirePresence('source_asset', 'create')
            ->notEmptyString('source_asset');

        $validator
            ->scalar('destination_asset')
            ->maxLength('destination_asset', 65)
            ->requirePresence('destination_asset', 'create')
            ->notEmptyString('destination_asset');

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
        return $rules;
    }

    /**
     * Saves a transfer
     *
     * @param int $block The block of the transfer
     * @param string $tx_id The transaction ID
     * @param string $from The address from where the transfer is originating
     * @param string $source_asset The asset from which the transfer comes
     * @param string $to The address to where the transfer is done
     * @param string $destination_asset The asset from which the transfer goes
     * @param int $amount The amount of the transfer
     * @return bool
     */
    public function createTransfer(
        int $block, 
        string $tx_id, 
        string $from, 
        string $source_asset,
        string $to, 
        string $destination_asset,
        int $amount,
    ): bool {
        $transfer = $this->newEmptyEntity();
        $transfer->block_number = $block;
        $transfer->transaction_hash = $tx_id;
        $transfer->from = $from;
        $transfer->source_asset = $source_asset;
        $transfer->to = $to;
        $transfer->destination_asset = $destination_asset;
        $transfer->amount = $amount;

        $result = $this->save($transfer);

        return $result !== false;
    }

    /**
     * Retrieve a set of transfers belonging to an address
     *
     * @param string $wallet The address the transfers belong to
     * @return null|\Cake\ORM\ResultSet
     */
    public function getTransfers(string $wallet): null|ResultSet {
        $query = $this->find()
            ->where([
                'OR'=>['to'=>$wallet,'from'=>$wallet],
            ])
            ->order(['created' => 'DESC'])
            ->limit(20);
        return $query->all();
    }
}
