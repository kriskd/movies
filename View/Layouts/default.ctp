<?php $cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <title><?php echo $title_for_layout; ?></title>
    <?php
		echo $this->Html->meta('icon');
        echo $this->Html->meta('viewport', '"width=device-width, initial-scale=1.0');

		echo $this->Html->css('http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css');
        echo $this->Html->css('bootstrap.min');
        echo $this->Html->css('bootstrap-responsive.min');

		echo $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js');
		echo $this->Html->script('http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
		echo $this->Html->script('bootstrap.min');
        echo $this->Html->script('script');
		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
    </head>
    <body>
        <div class="container">
            <div id="content">
    
                <?php echo $this->Session->flash(); ?>
    
                <?php echo $this->fetch('content'); ?>
            </div>
            <div id="footer">
                <?php echo $this->Html->link(
                        $this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
                        'http://www.cakephp.org/',
                        array('target' => '_blank', 'escape' => false)
                    );
                ?>
            </div>
        </div>
	<?php echo $this->element('sql_dump'); ?>
    </body>
</html>