<?php
class m131023_130102_advertisement_unit extends CDbMigration
{
	public function safeUp() {
		$this->createTable(
			'advertisement_unit',
			array(
				'id'=>'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'advertisement_type_id' => 'int(11) NOT NULL',
				'title' => 'varchar(255) NOT NULL',				
				'description' => 'text',
				'cost'=>'int(11)',
				'impressions'=>'int(11)',
				'in_auction'=>'tinyint(1)',
				'auction_deadline'=>'int(11)',
				'created_at' => 'int(11)',
				'updated_at' => 'int(11)',
				'PRIMARY KEY (id)',
			),
			'ENGINE=InnoDB'
		);
	}

	public function safeDown() {
		$this->dropTable('advertisement_unit');
	}
}