<?php echo $this->Form->create('Movie'); ?>
    <?php echo $this->Form->input('id'); ?>
    <?php echo $this->Form->input('title', array('label' => 'Search')); ?>
<?php echo $this->Form->end('Save'); ?>
<?php if(isset($movies)): ?>
    <?php echo $this->element('movie_table', $movies); ?>
<?php endif; ?>