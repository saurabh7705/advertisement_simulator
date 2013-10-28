<?php $this->pageTitle=Yii::app()->name.' - '.$team->name; ?>
<div class="padit">
	<center><h1><?php echo $team->name; ?><h1></center>

	<h4 class="sub_head">
		<?php echo $team->product_name; ?>
		<?php if($team->company) { ?>
			 (Company- <?php echo $team->company; ?>)
		<?php } ?>
	</h4>
	<div><?php echo $team->product_description; ?></div>

	<div class="row-fluid m40">
		<div class="span8">
			<h4 class="sub_head">Bought Ad Units</h4>
			<?php if(count($team->unit_logs) > 0 ) { ?>
				<table class="table table-striped">
				<tr>
					<th>Title</th>
					<th>Description</th>
					<th>Cost</th>
					<th>Impressions</th>
					<th>Index</th>
				</tr>
				<?php foreach($team->unit_logs as $log_unit) { 
					$ad_unit = $log_unit->advertisement_unit; ?>
					<tr>
						<td><strong><?php echo $ad_unit->title; ?></strong></td>
						<td><?php echo $ad_unit->description; ?></p><br />
						<td><strong>Rs. <?php echo number_format($log_unit->amount); ?></strong></td>
						<td></strong><?php echo number_format($ad_unit->impressionsCount); ?></strong></td>
						<td></strong><?php echo $ad_unit->index; ?></strong></td>
					</tr>
				<?php } ?>
				</table>
			<?php } else { ?>
				<section class="highlight_block_blue">No ad units bought till now.</section>
			<?php } ?>
		</div>
		<div class="span4">
			<h4 class="sub_head">Current Balance</h4>
			<div>Rs. <?php echo number_format($team->leftBalance); ?></div>

			<h4 class="sub_head">Total Impressions</h4>
			<div><?php echo number_format($team->totalImpressions); ?></div>
		</div>
	</div>
	<br clear="all"/>
</div>