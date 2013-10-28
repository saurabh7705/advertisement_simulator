<?php

class m131028_075617_high_frequency_in_unit extends CDbMigration
{
	public function up()
	{
		$this->addColumn('advertisement_unit', 'high_frequency', 'tinyint(1) DEFAULT 0');
	}

	public function down()
	{
		echo "m131028_075617_high_frequency_in_unit does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}