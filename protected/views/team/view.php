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
					<th>Remove</th>
				</tr>
				<?php foreach($team->unit_logs as $log_unit) { 
					$ad_unit = $log_unit->advertisement_unit; ?>
					<tr>
						<td><strong><?php echo $ad_unit->title; ?></strong></td>
						<td><?php echo $ad_unit->description; ?></p><br />
						<td><strong>Rs. <?php echo number_format($log_unit->amount); ?></strong></td>
						<td></strong><?php echo number_format($ad_unit->impressionsCount); ?></strong></td>
						<td></strong><?php echo $ad_unit->index; ?></strong></td>
						<td><?php echo CHtml::link('Remove', array('/advertisementUnit/refund', 'id'=>$ad_unit->id), array('class'=>'btn btn-danger', 'confirm'=>"Remove this item?")); ?></td>
					</tr>
				<?php } ?>
				</table>
			<?php } else { ?>
				<section class="highlight_block_blue">No ad units bought till now.</section>
			<?php } ?>
			
			<?php $active_bids = $team->activeBids; ?>
			<h4 class="sub_head m30">Active Bids</h4>
			<?php if($active_bids) { ?>
				<table class="table table-striped">
				<tr>
					<th>Ad Unit</th>
					<th>Amount</th>
					<th>Created At</th>
				</tr>
				<?php foreach($active_bids as $active_bid) { ?>
					<tr>
						<td><?php echo CHtml::link($active_bid->advertisement_unit->title, array('/advertisementUnit/view', 'id'=>$active_bid->advertisement_unit_id), array('target'=>"_blank")); ?></td>
						<td><strong>Rs. <?php echo number_format($active_bid->amount); ?></strong></td>
						<td></strong><?php echo date("H:i D d-m-Y", $active_bid->created_at); ?></strong></td>
					</tr>
				<?php } ?>
				</table>
			<?php } else { ?>
				<section class="highlight_block_blue">No active bids at the moment.</section>
			<?php } ?>
		</div>
		<div class="span4">
			<h4 class="sub_head">Current Balance</h4>
			<div>Rs. <?php echo number_format($team->leftBalance); ?></div>

			<h4 class="sub_head">Total Impressions</h4>
			<div><?php echo number_format($team->totalImpressions); ?></div>
			
			<h4 class="sub_head">Notifications</h4>
			<ul>
				<?php foreach($team->notifications as $notification) { ?>
					<li><?php echo date("H:i d/m", $notification->created_at); ?> - <?php echo $notification->content; ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<br clear="all"/>
</div>