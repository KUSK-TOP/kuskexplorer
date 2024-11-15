<?php
declare(strict_types=1);

namespace App\Model\Table;


//use App\Model\Entity\Address;

//use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\ResultSet;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Addresses Model
 *
 * @method \App\Model\Entity\Address newEmptyEntity()
 * @method \App\Model\Entity\Address newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Address[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Address get($primaryKey, $options = [])
 * @method \App\Model\Entity\Address findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Address patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Address[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Address|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Address saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Address[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Address[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Address[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Address[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AddressesTable extends Table
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

        $this->setTable('addresses');
        $this->setDisplayField('address');
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
            ->scalar('address')
            ->maxLength('address', 50)
            ->requirePresence('address', 'create')
            ->notEmptyString('address');

        $validator
            ->notEmptyString('balance');

        $validator
            ->scalar('asset')
            ->maxLength('asset', 65)
            ->requirePresence('asset', 'create')
            ->notEmptyString('asset');

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
        $rules->add($rules->isUnique(['address', 'asset']), ['errorField' => 'address']);

        return $rules;
    }

    /**
     * Credit an amount to an address
     * 
     * @param string $wallet The address to credit
     * @param int $amount The amount to credit
     * @param string $asset The asset of the credit
     * @return bool
     */
    public function credit(string $wallet, int $amount, string $asset): bool {
        if (!empty($wallet)) {
            $query = $this->find()
            ->where(['address'=>$wallet, 'asset' => $asset]);

            $address = $query->first();

            if (!is_null($address)) {
                $address->balance += $amount;
                $result = $this->save($address);
            } else {
                $address = $this->newEmptyEntity();
                $address->address = $wallet;
                $address->asset = $asset;
                $address->balance = $amount;
                $result = $this->save($address);
            }
            
            return $result !== false;
        } else {
            throw new \Exception('The addresses is blank');
        }
    }

    /**
     * Debit an amount to an address
     * 
     * @param string $wallet The address to debit
     * @param int $amount The amount to debit
     * @param string $asset The asset of the debit
     * @return bool
     */
    public function debit(string $wallet, int $amount, string $asset): bool {
        if (!empty($wallet)) {
            $query = $this->find()
                ->where(['address'=>$wallet, 'asset'=>$asset]);

            $address = $query->first();

            if (!is_null($address)) {
                $address->balance -= $amount;
                $result = $this->save($address);
            } else {
                throw new \Exception("The address {$wallet} was not found.");
            }
            
            return $result !== false;
        } else {
            throw new \Exception('The addresses is blank');
        }
    }

    /**
     * Fetch an address
     * 
     * @param string $wallet The address we want to fetch
     * @return null|\Cake\ORM\Resultset
     */
    public function getAddress(string $wallet): null|ResultSet {
        if (!empty($wallet)) {
            $query = $this->find()
                ->where(['address'=>$wallet]);

            return $query->all();
        } else {
            throw new \Exception('The addresses is blank');
        }
    }

    /**
     * Return the top addresses with a specified limit
     * 
     * @param int $topLimit The limit of account to return
     * @return \Cake\ORM\ResultSet
     */
    public function top(int $topLimit = 100): ResultSet {
        $query = $this->find()
            ->order(['balance'=>'DESC'])
            ->where(
                ['address NOT IN' => 
                    [
                        'ey1qyrzek5uhs370fudscta5n6hc7uek6tgsnd6xxq',
                        'ey1qtvye6ql37hhhq7xshh3lj62ednurdxca2a4gnp',
                        'ey1qzk97yvt3cd8u6dh0a6rs53lyrecptq60mt4dmz',
                        'ey1qs6qdzxz4tkav8e7wsd4vh8gl9mntd3ganq5kaj',
                        'ey1q2lf7urr8xvykcc0xt0uatjcsx3tgdwkklu87na',
                        'BLANK OR ERROR',                    
                    ],
                    'asset' => 'ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff',
                    'balance >' => 0,
                ]
            )
            ->limit($topLimit);

        return $query->all();
    }
}
