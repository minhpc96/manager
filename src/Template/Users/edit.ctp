<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->user_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $user->user_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
            echo $this->Form->input('username');
            echo $this->Form->input('email');
            echo $this->Form->input('password');
            echo $this->Form->input('name');
            echo $this->Form->input('role', ['options' => ['admin' => 'Admin', 'user' => 'User'], 'empty' => false]);
            $ofdepartments = null;
            $i = 0;
            foreach ($user->managers as $manager) {
                $ofdepartments[$i] = $manager->department_id;
                $i++;
            }
            echo $this->Form->input('department_id', ['type' => 'select', 'multiple' => 'checkbox', 'options' => $departments, 'default' => $ofdepartments]);
            $managers = null;
            $i = 0;
            foreach ($user->managers as $manager) {
                if (!is_null($manager->isManager)) {
                    $managers[$i] = $manager->department_id;
                    $i++;
                }
            }
            echo $this->Form->input('manager', ['type' => 'select', 'multiple' => 'checkbox' , 'options' => $departments, 'default' => $managers]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
