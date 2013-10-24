<div class="padit">
	<h1>Teams</h1>

	<?php
	$this->renderPartial('admin',array(
	    'model' => $grid_model
	)); ?>
	<br />

	<h3>Create a Team</h3><br />
	<?php
	$this->renderPartial('_form',array(
	    'new_model' => $new_model
	)); ?>
</div>