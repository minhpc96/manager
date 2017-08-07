<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Manager Entity.
 *
 * @property int $user_id
 * @property \App\Model\Entity\User $user
 * @property int $department_id
 * @property \App\Model\Entity\Department $department
 * @property bool $isManager
 * @property \Cake\I18n\Time $del_lag
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modify
 */
class Manager extends Entity
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
        'department_id' => false,
    ];
}
