<?php if(is_string($movies)): ?>
    <p><?php echo $movies; ?></p>
    <?php return; ?>
<?php endif; ?>
<?php echo $this->Form->create('Movie'); ?>
    <?php echo $this->Form->input('id'); ?>
    <?php echo $this->Form->input('title', array('label' => 'Search for Movies')); ?>
<?php echo $this->Form->end(array('label' => 'Add to List', 'class' => 'btn btn-primary')); ?>
<?php if(isset($movies)): ?>
    <?php echo $this->element('movie_table', $movies); ?>
<?php endif; ?>
<?php echo $this->Html->link('Logout', array('controller' => 'movies', 'action' => 'logout'),
                             array('class' => 'btn btn-warning')); ?>
<?php echo $this->Html->div('confirm', '', array('title' => 'Confirm')); ?>