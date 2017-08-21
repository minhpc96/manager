<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $ParentUsers
 * @property \Cake\ORM\Association\HasMany $ChildUsers
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('users');
        $this->displayField('name');
        $this->primaryKey('user_id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Managers', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('username')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table'])
            ->add('username', [
                'minLength' => [
                    'rule' => ['minLength', 8],
                    'message' => 'The username need more 8 charater '
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 16],
                    'message' => 'The username is too long (max 16 charater)'
                ]
            ]);

        $validator
            ->add('email', 'valid', ['rule' => 'email'])
            ->notEmpty('email');

        $validator
            ->notEmpty('password')
            ->add('password', [
                'minLength' => [
                    'rule' => ['minLength', 8],
                    'message' => 'Too short! (Min is 8)'
                ],
                'ruleName' => [
                    'rule' => ['custom', '(^(?=.*?[a-z])(?=.*?[0-9]))'],
                    'message' => 'So simple! Password need both word and number'
                ]
            ]);

        $validator
            ->notEmpty('name')
            ->add('name', [
                'minLength' => [
                    'rule' => ['minLength', 10],
                    'message' => 'The name need more 10 charater '
                ],
            ]);

        $validator
            ->allowEmpty('role');

        $validator
            ->allowEmpty('avatar');


        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
}
