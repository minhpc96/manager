<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity.
 *
 * @property int $user_id
 * @property \App\Model\Entity\User $user
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $role
 * @property int $parent_id
 * @property \App\Model\Entity\User $parent_user
 * @property int $lft
 * @property int $rght
 * @property string|resource $avatar
 * @property \Cake\I18n\Time $del_flag
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modify
 * @property \App\Model\Entity\User[] $child_users
 */
class User extends Entity
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
        '*' => true,
        'user_id' => false,
    ];
    
    /**
     * Hash password before save in table
     */
    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
}
