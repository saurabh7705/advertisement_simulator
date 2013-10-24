<?php $this->pageTitle=Yii::app()->name . ' - Login'; ?>

<div class="padit">
	<h1>Login</h1>

	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); ?>

		<p class="note">Fields with <span class="required">*</span> are required.</p><br />

		<div>
			<label>Email</label>
			<?php echo $form->textField($model,'email'); ?>
			<?php echo $form->error($model,'email'); ?>
		</div><br />

		<div>
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password'); ?>
			<?php echo $form->error($model,'password'); ?>
		</div><br />

		<div class="rememberMe">
			<?php echo $form->checkBox($model,'rememberMe'); ?>
			<?php echo $form->label($model,'rememberMe'); ?>
			<?php echo $form->error($model,'rememberMe'); ?>
		</div><br />

		<div class="buttons">
			<?php echo CHtml::submitButton('Login', array('class'=>'btn btn-success')); ?>
		</div>

	<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>
