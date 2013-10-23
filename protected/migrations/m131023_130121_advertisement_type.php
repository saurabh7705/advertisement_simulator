<?php
class m131023_130121_advertisement_type extends CDbMigration
{
	public function safeUp() {
		$this->createTable(
			'advertisement_type',
			array(
				'id'=>'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(255) NOT NULL',
				'created_at' => 'int(11)',
				'updated_at' => 'int(11)',
				'PRIMARY KEY (id)',
			),
			'ENGINE=InnoDB'
		);
	}

	public function safeDown() {
		$this->dropTable('advertisement_type');
	}
}