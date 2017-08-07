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
        $this->addBehavior('Tree');

        $this->hasMany('Managers', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ParentUsers', [
            'className' => 'Users',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildUsers', [
            'className' => 'Users',
            'foreignKey' => 'parent_id'
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
//            ->requirePresence('username', 'create')
            ->notEmpty('username')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->add('email', 'valid', ['rule' => 'email'])
//            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->notEmpty('password');

        $validator
//            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->allowEmpty('role');

        $validator
            ->add('lft', 'valid', ['rule' => 'numeric'])
//            ->requirePresence('lft', 'create')
            ->notEmpty('lft');

        $validator
            ->add('rght', 'valid', ['rule' => 'numeric'])
//            ->requirePresence('rght', 'create')
            ->notEmpty('rght');

        $validator
//            ->requirePresence('avatar', 'create')
            ->allowEmpty('avatar');

//        $validator
//            ->add('created', 'valid', ['rule' => 'datetime'])
//            ->requirePresence('created', 'create')
//            ->notEmpty('created');
//
//        $validator
//            ->add('modify', 'valid', ['rule' => 'datetime'])
//            ->requirePresence('modify', 'create')
//            ->notEmpty('modify');

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
