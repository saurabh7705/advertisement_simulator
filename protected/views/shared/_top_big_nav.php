<div id="team_nav">
<div class="container" >
	<div class="row">
    	<div class="span3">
    		<a href="<?php echo Yii::app()->createUrl("/site/distribution"); ?>" title="Indian Advertising League" id="logo"><img src="<?php echo Yii::app()->baseUrl; ?>/images/ial_logo.png" alt="Indian Advertising League" /></a>
        </div>
        <div class="span9">
            <nav>
            	<ul class="top_nav">
					<li><?php echo CHtml::link('Information', array('/site/distribution')); ?></li>
            		<?php if(Yii::app()->user->isAdmin) { ?>
	            		<li><?php echo CHtml::link('Teams', array('/admin/team/index')); ?></li>
	            		<li><?php echo CHtml::link('Ad Groups', array('/admin/advertisementType/index')); ?></li>
	            		<li><?php echo CHtml::link('Ad Units', array('/admin/advertisementUnit/index')); ?></li>
	            		<li><?php echo CHtml::link('Logout', array('/site/logout')); ?></li>
            		<?php } ?>
            		<?php if(Yii::app()->user->isTeam) { ?>
	            		<li><?php echo CHtml::link('Dashboard', array('/team/view')); ?></li>
	            		<li><?php echo CHtml::link('Ad Units', array('/advertisementUnit/index')); ?></li>
	            		<li><?php echo CHtml::link('Logout', array('/site/logout')); ?></li>
            		<?php } ?>
            		<?php if(Yii::app()->user->isGuest) { ?>
	            		<li><?php echo CHtml::link('Login', array('/site/login')); ?></li>
            		<?php } ?>
					<li id="clock" title="Hitwicket Server Time. All actions in the game are synced to this time"></li>
            	</ul> 
            </nav>
        </div>
    </div>
</div>
</div>
<script>
$(document).ready(function() {
	var time = new Date(<?php echo date("Y,m-1,d,H,i,s"); ?>);
	ShowClock(time);
});
</script>
