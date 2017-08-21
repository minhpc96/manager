<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?php
            echo $this->Form->input('username');
            echo $this->Form->input('email');
            echo $this->Form->input('password');
            echo $this->Form->input('name');
            echo $this->Form->input('role', ['options' => ['admin' => 'Admin', 'user' => 'User'], 'empty' => false]);
            echo $this->Form->input('department_id', ['type' => 'select', 'multiple' => 'checkbox' , 'options' => $departments]);
            echo $this->Form->input('manager', ['type' => 'select', 'multiple' => 'checkbox' , 'options' => $departments]);
            echo $this->Form->input('avatar', ['type'=>'file']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
