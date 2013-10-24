<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'team_grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'ajaxUrl'=>array('/admin/advertisementType/admin'),
		'columns'=>array(
			'id',
			'name',
			array(
				'class'=>'CButtonColumn',
				'template'=>'{update}{delete}',
				'updateButtonUrl' => 'Yii::app()->createUrl("/admin/advertisementType/index", array("id" => $data->id))',
				'deleteButtonUrl' => 'Yii::app()->createUrl("/admin/advertisementType/destroy", array("id" => $data->id))',
				'deleteConfirmation'=>'Do you really want to delete this advertisement group?'
			),
		),
	));
?>