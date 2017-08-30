<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <?php if ($current_user['lastLogin'] != null) : ?>
        <li><?= $this->Html->link(__('Profile'), ['action' => 'view', $current_user['user_id']]) ?></li>
        <?php endif ?>
    </ul>
</nav>
<div class="categories form large-9 medium-8 columns content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Change Password') ?></legend>
        <?php
            echo $this->Form->input('oldpassword', ['type' => 'password', 'label' => 'Old Password']);
            echo $this->Form->input('newpassword', ['type' => 'password', 'label' => 'New Password']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>
