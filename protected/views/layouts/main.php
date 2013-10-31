<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<?php $base_url = Yii::app()->baseUrl; ?>
	<link rel="stylesheet/less" href="<?php echo $base_url; ?>/less/bootstrap-responsive.less">            
    <!--[if IE 8]> <link rel="stylesheet/less" href="<?php echo $base_url; ?>/less/ie8.less"></link><![endif]-->
    <link rel="stylesheet/less" href="<?php echo $base_url; ?>/less/style.less">	
	<link rel="stylesheet" type="text/css" media="screen,projection" href="<?php echo $base_url; ?>/css/ui.totop.css" />
	<link rel="stylesheet" type="text/css" media="screen,projection" href="<?php echo $base_url; ?>/css/jquery.pointpoint.css" />
    <script src="<?php echo $base_url; ?>/js/libs/less-1.3.0.min.js"></script>
	<?php
	$cs = Yii::app()->clientScript;
	$cs->scriptMap = array('jquery.js'=>$base_url.'/js/jquery-1.7.1.min.js'); 
	$cs->registerCoreScript('jquery');
	Yii::app()->clientScript->addPackage('other-required-scripts', array(
		'baseUrl'=>$base_url, // or basePath
		'js'=>array(
			"js/libs/modernizr-2.5.3-respond-1.1.0.min.js",
			"js/easing.js",
			"js/jquery.history.js",
			"js/libs/bootstrap/bootstrap.min.js",
			"js/plugins.js",
			"js/script.js",
			"js/jquery.ui.totop.min.js",
			"js/jquery.colorbox-min.js",
			"js/transform.js",
			"js/clock.js",
		),
		'depends'=>array('jquery')
	));
	Yii::app()->clientScript->registerPackage('other-required-scripts');
	?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
	<?php $this->renderPartial('//shared/_top_big_nav'); ?>
	<div class="container" id="main_container">        
		<div class="row">
			<div id="main">                    
				<article>
					<?php foreach(Yii::app()->user->getFlashes() as $key => $message) { ?>
					    <div class="alert alert-<?php echo $key; ?>"><?php echo $message; ?><a class="close" data-dismiss="alert" href="#">&times;</a></div>
					<?php } ?>
					<?php echo $content; ?>
				</article>
			</div><!--end #main -->
		</div><!--end row -->
	</div><!--end #main_container -->
</body>
</html>