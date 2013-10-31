<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'team_grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'ajaxUrl'=>array('/admin/advertisementUnit/admin'),
		'columns'=>array(
			'id',
			array(
				'name'=>'title',
				'type'=>"raw",
				'value'=>'CHtml::link($data->title, array("/advertisementUnit/view", "id"=>$data->id), array("target"=>"_blank"))'
			),
			'cost',
			'impressions',
			'index',
			'stars',
			array(
				'name'=>'high_frequency',
				'value'=>'$data->high_frequency == 1 ? "Yes" : "No"',
				'filter' => CHtml::dropDownList(
					'AdvertisementUnit[high_frequency]',
					$model->high_frequency,
					array('0' => 'No', '1' => 'Yes'),
					array('prompt' => 'All')
				),
			),
			//'description',
			array(
				'name'=>'advertisement_type_name',
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
	            'name'=>'active_bid_id',
	            'header'=>'Active Bid',
	            'value'=>'($data->active_bid) ? number_format($data->active_bid->amount) : "" ',
	            'filter' => ''
	        ),
	        /*array(
			    'class'=>'CButtonColumn',
			    'template'=>'{start_auction}',
			    'buttons'=>array
			    (
			        'start_auction' => array
			        (
			            'label'=>'Start Auction',
			            'url'=>'Yii::app()->createUrl("/admin/advertisementUnit/startAuction", array("id"=>$data->id))',
						'visible' => '($data->in_auction == 1 && $data->auction_status == AdvertisementUnit::AUCTION_INACTIVE)',
						'options' => array(
							'confirm' => 'Sure you want to start auction of this ad unit?',
						),
			        ),
			    )
			),*/
			array(
			    'class'=>'CButtonColumn',
			    'template'=>'{duplicate_unit}',
			    'buttons'=>array
			    (
			        'duplicate_unit' => array
			        (
			            'label'=>'Duplicate it',
			            'url'=>'Yii::app()->createUrl("/admin/advertisementUnit/duplicate", array("id"=>$data->id))',
						'options' => array(
							'confirm' => 'Sure you want to duplicate this ad unit?',
						),
			        ),
			    )
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