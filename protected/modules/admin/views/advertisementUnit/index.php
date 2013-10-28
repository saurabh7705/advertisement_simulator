<div class="padit">
	<h1>Ad Units</h1>

	<?php $types = AdvertisementType::model()->findAll(); ?>

	<div class="team_tab">
		<ul class="nav nav-tabs m20">
			<?php foreach($types as $type) { ?>
				<li class="<?php echo ($advertisement_type_id == $type->id) ? 'active' : ''; ?>">
					<?php echo CHtml::link($type->name, array('/admin/advertisementUnit/index', 'advertisement_type_id'=>$type->id)); ?>
				</li>
			<?php } ?>
		</ul>
	</div>

	<?php
	$this->renderPartial('admin',array(
	    'model' => $grid_model
	)); ?>
	<br />

	<h3>Create an Ad Unit</h3><br />
	<?php
	$this->renderPartial('_form',array(
	    'new_model' => $new_model
	)); ?>
</div>