<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Department Entity.
 *
 * @property int $department_id
 * @property \App\Model\Entity\Department $department
 * @property string $department_name
 * @property string $detail
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $del_flag
 * @property \Cake\I18n\Time $modify
 */
class Department extends Entity
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
        'department_id' => false,
    ];
}
