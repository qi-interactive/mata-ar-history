<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

use yii\db\Schema;
use yii\db\Migration;

class m150604_162832_addstatuscomment extends Migration
{
    
	public function safeUp()
    {
        $this->addColumn('{{%arhistory_revision}}', 'Status', 'tinyint(1)');
        $this->addColumn('{{%arhistory_revision}}', 'Comment', 'TEXT');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%arhistory_revision}}', 'Status');
        $this->dropColumn('{{%arhistory_revision}}', 'Comment');
    }

}
