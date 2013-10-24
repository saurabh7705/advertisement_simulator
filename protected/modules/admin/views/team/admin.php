<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'team_grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'ajaxUrl'=>array('/admin/team/admin'),
		'columns'=>array(
			'id',
			array(
				'name'=>'name',
				'value'=>'CHtml::link($data->name, array("/team/view", "id"=>$data->id))',
				'type'=>'raw'
			),
			'email',
			'product_name',
			//'product_description',
			array(
				'name'=>'finance_amount',
				'value'=>'number_format($data->finance_amount)'
			),
			array(
				'header'=>'Impressions',
				'value'=>'number_format($data->totalImpressions)'
			),
			array(
				'class'=>'CButtonColumn',
				'template'=>'{update}{delete}',
				'updateButtonUrl' => 'Yii::app()->createUrl("/admin/team/index", array("id" => $data->id))',
				'deleteButtonUrl' => 'Yii::app()->createUrl("/admin/team/destroy", array("id" => $data->id))',
				'deleteConfirmation'=>'Do you really want to delete this team?'
			),
		),
	));
?>