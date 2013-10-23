<?php
class m131023_130436_finance_log extends CDbMigration
{
	public function safeUp() {
		$this->createTable(
			'finance_log',
			array(
				'id'=>'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'team_id' => 'int(11) NOT NULL',
				'advertisement_unit_id'=> 'int(11) NOT NULL',
				'amount' => 'int(11) NOT NULL',
				'created_at' => 'int(11)',
				'updated_at' => 'int(11)',
				'PRIMARY KEY (id)',
			),
			'ENGINE=InnoDB'
		);
	}

	public function safeDown() {
		$this->dropTable('finance_log');
	}
}