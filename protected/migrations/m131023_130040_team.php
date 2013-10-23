<?php
class m131023_130040_team extends CDbMigration
{
	public function safeUp() {
		$this->createTable(
			'team',
			array(
				'id'=>'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(255) NOT NULL',
				'email' => 'varchar(255) NOT NULL',
				'password' => 'varchar(255) NOT NULL',
				'product_name' => 'varchar(255) NOT NULL',
				'product_description' => 'text',
				'finance_amount'=>'int(11)',
				'created_at' => 'int(11)',
				'updated_at' => 'int(11)',
				'PRIMARY KEY (id)',
			),
			'ENGINE=InnoDB'
		);
	}

	public function safeDown() {
		$this->dropTable('team');
	}
}