<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Department'), ['action' => 'edit', $department->department_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Department'), ['action' => 'delete', $department->department_id], ['confirm' => __('Are you sure you want to delete # {0}?', $department->department_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Departments'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Department'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="departments view large-9 medium-8 columns content">
    <h3><?= h($department->department_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Department ID') ?></th>
            <td><?= h($department->department_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Department Name') ?></th>
            <td><?= h($department->department_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Detail') ?></th>
            <td><?= h($department->detail) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($department->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modify') ?></th>
            <td><?= h($department->modify) ?></td>
        </tr>
    </table>
</div>
