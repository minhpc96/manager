<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Active Account') ?></li>
        <li><?= $this->Html->link(__('Login'), ['action' => 'login']) ?></li>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create() ?>
    <?= $this->Form->end() ?>
</div>

