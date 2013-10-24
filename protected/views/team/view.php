<?php $this->pageTitle=Yii::app()->name.' - '.$team->name; ?>
<div class="padit">
	<center><h1><?php echo $team->name; ?><h1></center>

	<h4 class="sub_head"><?php echo $team->product_name; ?></h4>
	<div><?php echo $team->product_description; ?></div>

	<div class="row-fluid m40">
		<div class="span8">
			<h4 class="sub_head">Bought Ad Units</h4>
			<?php if(count($team->unit_logs) > 0 ) { 
				foreach($team->unit_logs as $log_unit) { 
					$ad_unit = $log_unit->advertisement_unit; ?>
					<div class="theme_item">
						<p><strong><?php echo $ad_unit->title; ?></strong></p>
						<p><?php echo $ad_unit->description; ?></p><br />
						<p>Cost: <strong>Rs. <?php echo number_format($ad_unit->cost); ?></strong></p>
						<p class="m10">Impressions: </strong><?php echo number_format($ad_unit->impressions); ?></strong></p>
					</div>
				<?php } ?>
			<?php } else { ?>
				<section class="highlight_block_blue">No ad units bought till now.</section>
			<?php } ?>
		</div>
		<div class="span4">
			<h4 class="sub_head">Left Balance</h4>
			<div>Rs. <?php echo number_format($team->finance_amount); ?></div>

			<h4 class="sub_head">Total Impressions</h4>
			<div><?php echo number_format($team->totalImpressions); ?></div>
		</div>
	</div>
	<br clear="all"/>
</div>