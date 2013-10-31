<?php
class m131030_123759_notification extends CDbMigration
{
	public function safeUp() {
		$this->createTable(
			'notification',
			array(
				'id'=>'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'team_id' => 'int(11) NOT NULL',
				'action_item_type' => 'varchar(255) NOT NULL',
				'action_id' => 'int(11) NOT NULL',
				'created_at' => 'int(11)',
				'updated_at' => 'int(11)',
				'PRIMARY KEY (id)',
			),
			'ENGINE=InnoDB'
		);
	}

	public function safeDown() {
		$this->dropTable('notification');
	}
}