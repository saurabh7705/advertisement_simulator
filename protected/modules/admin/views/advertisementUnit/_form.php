<?php $types = AdvertisementType::model()->findAll(array('order'=>'name')); ?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'team-form',
		'enableAjaxValidation'=>false,
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
			<?php echo $form->labelEx($new_model,'description'); ?>
			<?php echo $form->textArea($new_model,'description',array('class'=>'big_ta')); ?>
		</div>

		<div>
			<?php echo $form->labelEx($new_model,'in_auction'); ?>
			<?php echo $form->checkBox($new_model,'in_auction',array('class'=>'in_auction_checkbox')); ?>
		</div>

		<div class="deadline_wrapper" style="display:<?php echo ($new_model->in_auction == 1) ? 'block' : 'none'; ?>;">
			<?php echo $form->labelEx($new_model,'auction_deadline'); ?>
			<?php echo $form->textField($new_model,'auction_deadline',array('data-format'=>"hh:mm:ss dd-MM-yyyy", 'class'=>'in_auction_checkbox')); ?>
			<span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
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