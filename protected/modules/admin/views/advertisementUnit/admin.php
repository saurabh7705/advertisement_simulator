<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'team_grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'ajaxUrl'=>array('/admin/advertisementUnit/admin'),
		'columns'=>array(
			'id',
			'title',
			'cost',
			'impressions',
			'description',
			array(
				'name'=>'advertisement_type_id',
				'value'=>'$data->advertisement_type->name'
			),
			array(
				'header'=>'Infinite/Auction',
				'value'=>'($data->in_auction == 1) ? "Auction" : "Infinite"',
			),
			array(
	            'name'=>'auction_deadline',
	            'value'=>'(!$data->auction_deadline) ? $data->auction_deadline : (date("H:i D d-m-Y",$data->auction_deadline))',
	            'filter' => ''
	        ),
			array(
				'class'=>'CButtonColumn',
				'template'=>'{update}{delete}',
				'updateButtonUrl' => 'Yii::app()->createUrl("/admin/advertisementUnit/index", array("id" => $data->id))',
				'deleteButtonUrl' => 'Yii::app()->createUrl("/admin/advertisementUnit/destroy", array("id" => $data->id))',
				'deleteConfirmation'=>'Do you really want to delete this ad unit?'
			),
		),
	));
?>