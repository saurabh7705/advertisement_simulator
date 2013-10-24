<?php $this->pageTitle=Yii::app()->name.' - '.$model->title; ?>
<div class="padit">
	<center><h1><?php echo $model->title; ?> (<?php echo $model->advertisement_type->name; ?>)<h1></center>

	<section class="highlight_block_blue m40"><?php echo $model->description; ?></section>

	<div class="theme_item">
		<p>Cost: <strong>Rs. <?php echo number_format($model->cost); ?></strong></p>
		<p class="m10">Impressions: <strong><?php echo number_format($model->impressions); ?></strong></p>
	</div><br /><br />

	<?php if($model->auctionStarted()) { ?>
		<div id="bid_area_for_current_transfer">
			<?php echo $this->renderPartial('_bid',array('unit'=>$model,'default_bid'=>$model->minAllowedBidAmount)); ?>
		</div>
	<?php }
	else if( !$team->unit_log(array('params'=>array('unit_id'=>$model->id))) ) { ?>
		<center><?php echo CHtml::link('Buy', array('/advertisementUnit/buy', 'id'=>$model->id), array('class'=>'btn btn-large btn-success buy_button')); ?></center>
	<?php }
	else { ?>
		<section class="warning_message">This unit has been already bought by your team.</section>
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