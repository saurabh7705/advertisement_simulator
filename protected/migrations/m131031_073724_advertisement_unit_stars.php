<?php
class m131031_073724_advertisement_unit_stars extends CDbMigration
{
	public function safeUp() {
		$this->addColumn('advertisement_unit', 'stars', 'varchar(255)');
	}

	public function safeDown() {
		$this->dropColumn('advertisement_unit', 'stars');
	}
}