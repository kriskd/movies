<div class="ui-corner-all auth">
    <p>Please authenticate with your Gmail account to view your movies.</p>
    <p>Movies App only collects your Gmail email address.</p>
    <?php echo $this->Html->link('Authenticate', array('controller' => 'movies', 'action' => 'my-movies'), array('class' => 'btn btn-success')); ?>
</div>