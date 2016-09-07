<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

use yii\db\Schema;
use yii\db\Migration;

class m160907_151328_alter_document_id extends Migration
{
	public function safeUp()
	{
		$this->alterColumn('{{%arhistory_revision}}', 'DocumentId', Schema::TYPE_STRING . '(128) NOT NULL');
	}

	public function safeDown()
	{
		$this->alterColumn('{{%arhistory_revision}}', 'DocumentId', Schema::TYPE_STRING . '(64) NOT NULL');
	}
}
