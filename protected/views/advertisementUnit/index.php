<div class="padit">
	<center><h1>Ad Units<h1></center><br />
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
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'team_grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'ajaxUrl'=>array('/advertisementUnit/filter'),
			'columns'=>array(
				array(
					'name'=>'title',
					'value'=>'CHtml::link($data->title, array("/advertisementUnit/view", "id"=>$data->id))',
					'type'=>'raw'
				),
				'cost',
				'impressions',
				'index',
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
					'class'=>'CButtonColumn',
					'template'=>'{view}',
					'viewButtonUrl' => 'Yii::app()->createUrl("/advertisementUnit/view", array("id" => $data->id))',
				),
			),
		));
	?>
</div>
