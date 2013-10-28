<?php $this->pageTitle=Yii::app()->name.' - '.$model->title; ?>
<div class="padit">
	<center><h1><?php echo $model->title; ?> (<?php echo $model->advertisement_type->name; ?>)<h1></center>

	<section class="highlight_block_blue m40"><?php echo $model->description; ?></section>

	<div class="theme_item">
		<div class="span5 ml0">
			<p>Cost: <strong>Rs. <?php echo number_format($model->cost); ?></strong></p>
			<p class="m10">Impressions: <strong><?php echo number_format($model->impressions); ?></strong></p>
			<p class="m10">Index: </strong><?php echo $model->index; ?></strong></p>
		</div>
		<div class="span5">
			<?php if($model->file_name){ ?>
				<img src="<?php echo Yii::app()->baseUrl;?>/ad_units/<?php echo $model->id.".".$model->extension; ?>" />
			<?php } ?>
		</div>
		<br clear="all" />
	</div><br />

	<?php if(Yii::app()->user->isTeam) { ?>
	<?php if($model->auctionStarted()) { ?>
		<div id="bid_area_for_current_transfer">
			<?php echo $this->renderPartial('_bid',array('unit'=>$model,'default_bid'=>$model->minAllowedBidAmount)); ?>
		</div>
	<?php }
	else if( !$team->unit_log(array('params'=>array('unit_id'=>$model->id))) ) { ?>
		<center><?php echo CHtml::link('Buy', array('/advertisementUnit/buy', 'id'=>$model->id), array('class'=>'btn btn-large btn-success buy_button')); ?></center>
	<?php }
	else { ?>
		<?php if($model->in_auction == 1) { ?>
			<section class="warning_message tac">This unit has been already bought by your team.</section>
		<?php } else { ?>
			<section class="warning_message tac">
				This unit has been already bought by your team.
				<?php echo CHtml::link('Refund', array('/advertisementUnit/refund', 'id'=>$model->id), array('class'=>'btn btn-large btn-success refund_button ml10')); ?>
			</section>
		<?php } ?>
	<?php } ?>
	<?php } ?>
</div>

<script>
$('.buy_button').click(function(){
	if(!$(this).hasClass('submitted')) {
		if(confirm('Are you sure you want to buy this ad unit?')) {
			$(this).addClass('submitted');
			return true;
		}
		else
			return false;	
	}
	else
		return false;
});

$('.refund_button').click(function(){
	if(!$(this).hasClass('submitted')) {
		if(confirm('If you cancel this ad unit, you will get the refund back. Are you sure?')) {
			$(this).addClass('submitted');
			return true;
		}
		else
			return false;	
	}
	else
		return false;
});

$("#refresh_bid").live('click',function(){
	$('#loader_bid').show();
   	$.ajax({
        'type':'post',
        'data':{"id":<?php echo $model->id; ?>},
        'url':"<?php echo Yii::app()->createUrl('/advertisementUnit/computeDefaultBid'); ?>",
        'cache':false,
        'success':function(html){
          if(html != 'error') {
            if(html == 'transferred')
                location.reload();
            else {
                $("#bid_area_for_current_transfer").html(html);
                $('#time_of_refresh').show();
            }
          }
      	}
    });  
});
</script>