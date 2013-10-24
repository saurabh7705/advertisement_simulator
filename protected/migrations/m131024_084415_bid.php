<?php
class m131024_084415_bid extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable(
			'bid',
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

		$this->addColumn('advertisement_unit', 'auction_status', 'tinyint(1) DEFAULT 0');
		$this->addColumn('advertisement_unit', 'active_bid_id', 'int(11)');
	}

	public function safeDown() {
		$this->dropTable('bid');
	}
}