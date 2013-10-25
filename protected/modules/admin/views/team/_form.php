<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'team-form',
		'enableAjaxValidation'=>false,
	)); ?>

		<?php if($form->errorSummary($new_model) != "") { ?>
			<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">&times;</a><?php echo $form->errorSummary($new_model); ?></div>
		<?php } ?>
	
		<div>
			<?php echo $form->labelEx($new_model,'name'); ?>
			<?php echo $form->textField($new_model,'name'); ?>
		</div><br />

		<div>
			<?php echo $form->labelEx($new_model,'email'); ?>
			<?php echo $form->textField($new_model,'email'); ?>
		</div><br />

		<div>
			<?php echo $form->labelEx($new_model,'product_name'); ?>
			<?php echo $form->textField($new_model,'product_name'); ?>
		</div><br />
		
		<div>
			<?php echo $form->labelEx($new_model,'product_description'); ?>
			<?php echo $form->textArea($new_model,'product_description',array('class'=>'big_ta')); ?>
		</div><br />

		<div>
			<?php echo $form->labelEx($new_model,'company'); ?>
			<?php echo $form->textField($new_model,'company'); ?>
		</div>
		
		<?php if($new_model->isNewRecord) { ?>
			<br />
			<div>
				<?php echo $form->labelEx($new_model,'password'); ?>
				<?php echo $form->passwordField($new_model,'password'); ?>
			</div><br />
			
			<div>
				<label class="required" for="Team_password_repeat">
					Confirm Password
					<span class="required">*</span>
				</label>
				<?php echo $form->passwordField($new_model,'password_repeat', array('title'=>'re-enter your password')); ?>
			</div>
		<?php } ?>
		
		<div class="buttons">
			<?php echo CHtml::submitButton('Submit', array('class'=>'btn btn-success')); ?>
		</div>

	<?php $this->endWidget(); ?>

</div>