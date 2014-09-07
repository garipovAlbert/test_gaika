<?php
return array_replace([
	'class' => 'yii\db\Connection',
	'dsn' => 'mysql:host=localhost;dbname=test_gaika',
	'username' => 'root',
	'password' => 'test',
	'charset' => 'utf8',
], require(__DIR__ . '/db-local.php'));
