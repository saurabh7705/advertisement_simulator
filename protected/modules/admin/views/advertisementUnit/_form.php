<?php $types = AdvertisementType::model()->findAll(array('order'=>'name')); ?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'team-form',
		'enableAjaxValidation'=>false,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
	)); ?>

		<?php if($form->errorSummary($new_model) != "") { ?>
			<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">&times;</a><?php echo $form->errorSummary($new_model); ?></div>
		<?php } ?>
	
		<div>
			<?php echo $form->labelEx($new_model,'title'); ?>
			<?php echo $form->textField($new_model,'title'); ?>
		</div><br />

		<div>
			<?php echo $form->labelEx($new_model,'advertisement_type_id'); ?>
			<?php echo $form->dropDownList($new_model,'advertisement_type_id', CHtml::listData($types,'id', 'name')); ?>
		</div><br />

		<div>
			<?php echo $form->labelEx($new_model,'cost'); ?>
			<?php echo $form->textField($new_model,'cost'); ?>
		</div><br />

		<div>
			<?php echo $form->labelEx($new_model,'impressions'); ?>
			<?php echo $form->textField($new_model,'impressions'); ?>
		</div><br />

		<div>
			<?php echo $form->labelEx($new_model,'index'); ?>
			<?php echo $form->textField($new_model,'index'); ?>
		</div><br />

		<div>
			<?php echo $form->labelEx($new_model,'high_frequency'); ?>
			<?php echo $form->checkBox($new_model,'high_frequency'); ?>
		</div><br />

		<div class="deadline_wrapper" style="display:<?php echo ($new_model->in_auction == 1) ? 'block' : 'none'; ?>;">
			<?php echo $form->labelEx($new_model,'auction_deadline'); ?>
			<?php $deadline = $new_model->auction_deadline ? (date('H:i:s d-m-Y', $new_model->auction_deadline)) : ''; ?>
			<?php echo $form->textField($new_model,'auction_deadline',array('data-format'=>"hh:mm:ss dd-MM-yyyy", 'class'=>'in_auction_checkbox', 'value'=>$deadline)); ?>
			<span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
			<br />
		</div>		

		<div>
			<?php echo $form->fileField($new_model,'file_name'); ?>
			<?php if($new_model->file_name){ ?>
				<br />
				<img style="width:200px;" src="<?php echo Yii::app()->baseUrl;?>/ad_units/<?php echo $new_model->id.".".$new_model->extension; ?>" />
			<?php } ?>
		</div><br />
		
		<div>
			<?php echo $form->labelEx($new_model,'description'); ?>
			<?php echo $form->textArea($new_model,'description',array('class'=>'big_ta')); ?>
		</div>
		
		<div class="buttons">
			<?php echo CHtml::submitButton('Submit', array('class'=>'btn btn-success')); ?>
		</div>

	<?php $this->endWidget(); ?>

</div>

<script src="<?php echo Yii::app()->baseUrl; ?>/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/css/bootstrap-datetimepicker.min.css" type="text/css" media="screen"/>

<script>
$(function() {
	$('.deadline_wrapper').datetimepicker({pickSeconds: false});
});

$('.in_auction_checkbox').change(function(){
	if ($(this).is(':checked'))
		$('.deadline_wrapper').show();
  	else
  		$('.deadline_wrapper').hide();
});
</script>