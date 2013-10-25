<?php
class m131025_125643_more_cols_in_unit extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('advertisement_unit', 'index', 'varchar(255)');
		$this->addColumn('advertisement_unit', 'file_name', 'varchar(255)');
		$this->addColumn('advertisement_unit', 'extension', 'varchar(255)');
	}

	public function safeDown()
	{
		$this->dropColumn('advertisement_unit', 'index');
		$this->dropColumn('advertisement_unit', 'file_name');
		$this->dropColumn('advertisement_unit', 'extension');
	}
}