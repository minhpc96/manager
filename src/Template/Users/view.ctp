<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->user_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->user_id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->user_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('Change Password'), [ 'action' => 'changepassword', $current_user['user_id']]) ?></li>
        <li><?= $this->Html->link(__('Logout'), ['action' => 'logout']) ?></li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->name) ?></h3>
    <?= $this->Html->image(!empty($user->avatar) ? $user->avatar : 'cake.icon.png') ?>
    <table class="vertical-table">
        <tr>
            <th><?= __('User ID') ?></th>
            <td><?= $user->user_id ?></td>
        </tr>
        <tr>
            <th><?= __('Username') ?></th>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($user->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Role') ?></th>
            <td><?= h($user->role) ?></td>
        </tr>
        <tr>
            <th><?= __('Manager') ?></th>
            <td>
                <?php
                    if (!empty($user->managers)) {
                        foreach ($user->managers as $manager) {
                            if (!is_null($manager->isManager)) {
                                echo $this->Html->link($manager->department->department_name, 
                                        ['controller' => 'Departments', 'action' => 'view', $manager->department_id]);
                                echo ' ';
                            }
                        }
                    }
                ?>
            </td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($user->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modify') ?></th>
            <td><?= h($user->modify) ?></td>
        </tr>
    </table>
    
    <div class="related">
        <h4><?= __('Related Departments') ?></h4>
        <?php if (!empty($user->managers)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Department Id') ?></th>
                <th><?= __('Department Name') ?></th>
            </tr>
            <?php foreach ($user->managers as $manager): ?>
            <tr>
                <td><?= h($manager->department_id) ?></td>
                <td>
                    <?=
                        $this->Html->link($manager->department->department_name, ['controller' => 'Departments', 'action' => 'view', $manager->department_id])
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>

    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($user->managers)): ?>
            <?php foreach ($user->managers as $manager): ?>
                <?php if (!is_null($manager->isManager)): ?>
                <table cellpadding="0" cellspacing="0">
                    <h5><?= h($manager->department->department_name)?></h5>
                    <tr>
                        <th><?= __('Name') ?></th>
                        <th><?= __('Email') ?></th>
                    </tr>
                    <?php foreach ($relatedusers as $related): ?>
                        <?php if ($manager->department_id == $related->department_id && $related->isManager == null): ?>
                        <tr>
                            <td>
                                <?= 
                                    $this->Html->link($related->user->name, ['controller' => 'Users', 'action' => 'view', $related->user_id]) 
                                ?>
                            </td>
                            <td>
                                <?= $related->user->email ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>   
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
