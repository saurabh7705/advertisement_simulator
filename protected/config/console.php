<?php
//DJJob::configure("mysql:host=localhost;dbname=advertisement_simulator", array( "mysql_user" => "root", "mysql_pass" => "root"));
DJJob::configure("mysql:host=localhost;dbname=advertisement_simulator", array( "mysql_user" => "root", "mysql_pass" => "clog186"));

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Adverb',

	// preloading 'log' component
	'preload'=>array('log'),

	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.vendor.*',
		'application.commands.*',
		'application.jobs.*',
	),

	// application components
	'components'=>array(
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=advertisement_simulator',
			'emulatePrepare' => true,
			'username' => 'root',
			//'password' => '112358',
			'password' => 'clog186',
			//'password' => 'root',
			'charset' => 'utf8',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);