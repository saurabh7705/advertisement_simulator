<div class="padit">
	<center><h1>Ad Units<h1></center><br />
	<?php $types = AdvertisementType::model()->findAll(); ?>

        <div class="team_tab">
                <ul class="nav nav-tabs m20">
                        <?php foreach($types as $type) { ?>
                                <li class="<?php echo ($advertisement_type_id == $type->id) ? 'active' : ''; ?>">
                                        <?php echo CHtml::link($type->name, array('/advertisementUnit/index', 'advertisement_type_id'=>$type->id)); ?>
                                </li>
                        <?php } ?>
                </ul>
        </div>
        
	<?php
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'team_grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'ajaxUrl'=>array('/advertisementUnit/filter', 'advertisement_type_id'=>$advertisement_type_id),
			'columns'=>array(
				array(
					'name'=>'title',
					'value'=>'CHtml::link($data->title, array("/advertisementUnit/view", "id"=>$data->id))',
					'type'=>'raw'
				),
				array(
					'name'=>"cost",
					'value'=>'number_format($data->cost)',
					'htmlOptions'=>array('style'=>'text-align: right; padding-right:4%')
				),
				/*array(
					'name'=>'impressions',
					'value'=>'number_format($data->impressions)',
					'visible'=>($advertisement_type_id == 1 || $advertisement_type_id == 3),
					'htmlOptions'=>array('style'=>'text-align: right; padding-right:4%')
				),*/				
				//'index',
				array(
					'name'=>'stars',
					'value'=>'"<div class=\"ad_unit_stars\" style=\"width:".($data->stars*20)."px;\">&nbsp;</div>"',
					'type'=>'raw',
					//'htmlOptions'=>array('style'=>'width:10%; text-align: right; padding-right: 1%;')
				),
				/*array(
					'name'=>'high_frequency',
					'value'=>'$data->high_frequency == 1 ? "Yes" : "No"',
					'filter' => CHtml::dropDownList(
						'AdvertisementUnit[high_frequency]',
						$model->high_frequency,
						array('0' => 'No', '1' => 'Yes'),
						array('prompt' => 'All')
					),
					'visible'=>($advertisement_type_id == 3)
				),*/
				//'description',
				/*array(
					'name'=>'advertisement_type_name',
					'value'=>'$data->advertisement_type->name'
				),*/
				array(
					'name'=>'auction_deadline',
					'value'=>'(!$data->auction_deadline) ? "Infinite" : (date("H:i D d-m-Y",$data->auction_deadline))',
					'filter' => '',
					'visible'=>($advertisement_type_id == 2 || $advertisement_type_id == 3)
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
