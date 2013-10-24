<?php

class m131024_085926_jobs extends CDbMigration
{
	public function up()
	{
		$command = "CREATE TABLE `jobs` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`handler` TEXT NOT NULL,
			`queue` VARCHAR(255) NOT NULL DEFAULT 'default',
			`attempts` INT UNSIGNED NOT NULL DEFAULT 0,
			`run_at` DATETIME NULL,
			`priority` TINYINT(1) DEFAULT 0,
			`locked_at` DATETIME NULL,
			`locked_by` VARCHAR(255) NULL,
			`failed_at` DATETIME NULL,
			`error` TEXT NULL,
			`created_at` DATETIME NOT NULL
			) ENGINE = INNODB;";
	   $this->execute($command);
	}

	public function down()
	{
		$this->dropTable('jobs');
	}
}