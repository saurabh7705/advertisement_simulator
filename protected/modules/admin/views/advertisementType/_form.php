<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'advertisement-type-form',
		'enableAjaxValidation'=>false,
	)); ?>

		<?php if($form->errorSummary($new_model) != "") { ?>
			<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">&times;</a><?php echo $form->errorSummary($new_model); ?></div>
		<?php } ?>
	
		<div>
			<?php echo $form->labelEx($new_model,'name'); ?>
			<?php echo $form->textField($new_model,'name'); ?>
		</div>
		
		<div class="buttons">
			<?php echo CHtml::submitButton('Submit', array('class'=>'btn btn-success')); ?>
		</div>

	<?php $this->endWidget(); ?>

</div>