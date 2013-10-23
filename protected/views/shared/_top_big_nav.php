<div id="team_nav">
<div class="container" >
	<div class="row">
    	<div class="span3">
    		<a href="<?php echo Yii::app()->createUrl("/site/index"); ?>" title="Online T20 Cricket Management Game | Hitwicket" id="logo"><img src="<?php echo Yii::app()->baseUrl; ?>/images/logo2.png" alt="Online T20 Cricket Management Game | Hitwicket" /></a>
        </div><!--end 3 -->
        <div class="span9">
            <nav>
            	<ul class="top_nav">
					<?php $league1_active_class = $league2_active_class = $team_active_class = $players_active_class = $matches_active_class = $profile_active_class = ''; ?>
					<?php 
					if($this->active_top_link == 'LEAGUE_1')
						$league1_active_class = 'active'; 
					if($this->active_top_link == 'LEAGUE_2')
						$league2_active_class = 'active';
					if($this->active_top_link == 'TEAM')
						$team_active_class = 'active';
					if($this->active_top_link == 'PLAYER')
						$players_active_class = 'active';
					if($this->active_top_link == 'MATCHES')
						$matches_active_class = 'active';
					if($this->active_top_link == 'PROFILE')
						$profile_active_class = 'active';
					?>
                    <li class=""><?php echo CHtml::link('<span>Division I</span>',array('/league/show', 'id'=>1),array('class'=>"$league1_active_class league")); ?></li>
                    <li class=""><?php echo CHtml::link('<span>Division II</span>',array('/league/show', 'id'=>2),array('class'=>"$league2_active_class league1_active_class league")); ?></li>
                    <li class=""><?php echo CHtml::link('<span>My Team</span>',array('/team/show','id'=>3),array('class'=>"$team_active_class team")); ?></li>
                    <li class=""><?php echo CHtml::link('<span>Players</span>',array('/players/index','id'=>3), array('class'=>"$players_active_class players")); ?></li>
                    <li class=""><?php echo CHtml::link('<span>Matches</span>',array('/team/matches', 'id'=>3),array('class'=>"$matches_active_class matches")); ?></li>
                    <li class=""><?php echo CHtml::link('<span>Profile</span>',array('/player/editProfile', 'id'=>3),array('class'=>"$profile_active_class top_profile")); ?></li>
            	</ul> 
            </nav>
        </div>
    </div>
</div>
</div>
