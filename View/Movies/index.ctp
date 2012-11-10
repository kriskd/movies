<?php echo $this->Form->create('Movie'); ?>
    <?php echo $this->Form->input('id'); ?>
    <?php echo $this->Form->text('title', array('label' => 'Search')); ?>
<?php echo $this->Form->end('Save'); ?>