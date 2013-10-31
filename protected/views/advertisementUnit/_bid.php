<section class="bid_section">
	<input type="button" value="Refresh" id="refresh_bid" class="btn" />
	<p class="m10">
		<?php 
		echo "Deadline: <strong>".date("H:i D d-m-Y",$unit->auction_deadline).'</strong>';
		echo '&nbsp;('.$unit->showBidCountdown().')';
		echo "<br />Asking price: <strong>".number_format($unit->cost).'</strong>'  ;

		if($unit->active_bid_id) {
			echo "<br />Highest bid: <strong>Rs ".number_format($unit->active_bid->amount);
			echo "</strong> by  ".$unit->active_bid->team->name;    
		} ?>

		<div id="loader_bid" style='display:none;'><img src="<?php echo Yii::app()->baseUrl; ?>/images/ajax-loader.gif"/></div>
		<span style="display:none;" id="time_of_refresh">&nbsp;(last refreshed at: <?php echo date('H:i',time()); ?>)</span>	
	</p>

	<?php $form=$this->beginWidget('CActiveForm', array('id'=>'activebids-form', 'enableAjaxValidation'=>false, 'action'=>array('bid/create'))); ?>           
		<label for="bid">Enter amount to bid on this ad unit:</label>
		<input type="hidden" name="unit_id" value="<?php echo $unit->id; ?>" />
		<div class="controls">
			<div class="input-prepend input-append">
				<span class="add-on">Rs </span>
				<input id="bid" name="bid" value="<?php echo $default_bid; ?>">
			</div>
		</div>
		<br />
		<?php echo CHtml::submitButton("Submit bid",array('class'=>'btn btn-success submit-bid-button btn-large')); ?>
	<?php $this->endWidget(); ?>

</section>

<script>	
$('#activebids-form').submit(function(event) {
	if($(this).hasClass('submitted')) {
		event.preventDefault();
		return false;
	}
	else {
		$(this).addClass('submitted')
		return true;
	}
});
</script>