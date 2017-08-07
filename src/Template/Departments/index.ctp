<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Department'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="departments index large-9 medium-8 columns content">
    <h3><?= __('Departments') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('department_id') ?></th>
                <th><?= $this->Paginator->sort('department_name') ?></th>
                <th><?= $this->Paginator->sort('detail') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('del_flag') ?></th>
                <th><?= $this->Paginator->sort('modify') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departments as $department): ?>
            <tr>
                <td><?= $department->department_id ?></td>
                <td><?= h($department->department_name) ?></td>
                <td><?= h($department->detail) ?></td>
                <td><?= h($department->created) ?></td>
                <td><?= h($department->del_flag) ?></td>
                <td><?= h($department->modify) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $department->department_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $department->department_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $department->department_id], ['confirm' => __('Are you sure you want to delete # {0}?', $department->department_id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
