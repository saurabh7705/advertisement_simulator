<?php

class m131025_133746_company_name extends CDbMigration
{
	public function up()
	{
		$this->addColumn('team', 'company', 'varchar(255)');
	}

	public function down()
	{
		echo "m131025_133746_company_name does not support migration down.\n";
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