<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <?php if ($current_user['role'] == 'admin') : ?>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->user_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $user->user_id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
        <?php endif; ?>
        <li><?= $this->Html->link(__('Change Password'), [ 'action' => 'changepassword', $current_user['user_id']]) ?></li>
        <li><?= $this->Html->link(__('Logout'), ['action' => 'logout']) ?></li>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
            echo $this->Html->image(!empty($user->avatar) ? $user->avatar : 'cake.icon.png');
            echo $this->Form->input('change avatar', ['type'=>'file', 'name' => 'avatar']);
            echo $this->Form->input('email');
            echo $this->Form->input('name');
            echo $this->Form->input('birthday');
            echo $this->Form->input('gender', ['type' => 'select', 'options' => ['0' => 'Male', '1' => 'Female', '2' => 'Other']]);
            if ($current_user['role'] == 'admin') :
                echo $this->Form->input('role', ['options' => ['admin' => 'Admin', 'user' => 'User'], 'empty' => false]);
                $ofdepartments = null;
                $i = 0;
                foreach ($user->managers as $manager) :
                    $ofdepartments[$i] = $manager->department_id;
                    $i++;
                endforeach;
                echo $this->Form->input('department_id', ['type' => 'select', 'multiple' => 'checkbox', 'options' => $departments, 'default' => $ofdepartments]);
                $managers = null;
                $i = 0;
                foreach ($user->managers as $manager) :
                    if (!is_null($manager->isManager)) :
                        $managers[$i] = $manager->department_id;
                        $i++;
                    endif;
                endforeach;
                echo $this->Form->input('manager', ['type' => 'select', 'multiple' => 'checkbox' , 'options' => $departments, 'default' => $managers]);
            endif;
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
