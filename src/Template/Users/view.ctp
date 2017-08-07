<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->user_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->user_id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->user_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $user->has('user') ? $this->Html->link($user->user->name, ['controller' => 'Users', 'action' => 'view', $user->user->user_id]) : '' ?></td>
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
            <th><?= __('Password') ?></th>
            <td><?= h($user->password) ?></td>
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
            <th><?= __('Parent User') ?></th>
            <td><?= $user->has('parent_user') ? $this->Html->link($user->parent_user->name, ['controller' => 'Users', 'action' => 'view', $user->parent_user->user_id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Lft') ?></th>
            <td><?= $this->Number->format($user->lft) ?></td>
        </tr>
        <tr>
            <th><?= __('Rght') ?></th>
            <td><?= $this->Number->format($user->rght) ?></td>
        </tr>
        <tr>
            <th><?= __('Del Flag') ?></th>
            <td><?= h($user->del_flag) ?></td>
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
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($user->child_users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('User Id') ?></th>
                <th><?= __('Username') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Password') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Role') ?></th>
                <th><?= __('Parent Id') ?></th>
                <th><?= __('Lft') ?></th>
                <th><?= __('Rght') ?></th>
                <th><?= __('Avatar') ?></th>
                <th><?= __('Del Flag') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modify') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->child_users as $childUsers): ?>
            <tr>
                <td><?= h($childUsers->user_id) ?></td>
                <td><?= h($childUsers->username) ?></td>
                <td><?= h($childUsers->email) ?></td>
                <td><?= h($childUsers->password) ?></td>
                <td><?= h($childUsers->name) ?></td>
                <td><?= h($childUsers->role) ?></td>
                <td><?= h($childUsers->parent_id) ?></td>
                <td><?= h($childUsers->lft) ?></td>
                <td><?= h($childUsers->rght) ?></td>
                <td><?= h($childUsers->avatar) ?></td>
                <td><?= h($childUsers->del_flag) ?></td>
                <td><?= h($childUsers->created) ?></td>
                <td><?= h($childUsers->modify) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $childUsers->user_id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $childUsers->user_id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $childUsers->user_id], ['confirm' => __('Are you sure you want to delete # {0}?', $childUsers->user_id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
