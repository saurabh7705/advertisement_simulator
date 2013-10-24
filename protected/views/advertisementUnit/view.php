<?php $this->pageTitle=Yii::app()->name.' - '.$model->title; ?>
<div class="padit">
	<center><h1><?php echo $model->title; ?> (<?php echo $model->advertisement_type->name; ?>)<h1></center>

	<section class="highlight_block_blue m40"><?php echo $model->description; ?></section>

	<div class="theme_item">
		<p>Cost: <strong>Rs. <?php echo number_format($model->cost); ?></strong></p>
		<p class="m10">Impressions: <strong><?php echo number_format($model->impressions); ?></strong></p>
	</div><br /><br />

	<?php if($model->in_auction == 1) {
		//
	}
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
})
</script>