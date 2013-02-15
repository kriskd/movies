<?php if(is_string($movies)): ?>
    <p><?php echo $movies; ?></p>
    <?php return; ?>
<?php endif; ?>
<?php echo $this->Form->create('Movie'); ?>
    <?php echo $this->Form->input('id'); ?>
    <?php echo $this->Form->input('title', array('label' => 'Search')); ?>
<?php echo $this->Form->end(array('value' => 'Save', 'class' => 'btn btn-primary')); ?>
<?php if(isset($movies)): ?>
    <?php echo $this->element('movie_table', $movies); ?>
<?php endif; ?>
<?php echo $this->Html->link('Logout', array('controller' => 'movies', 'action' => 'logout')); ?>