<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

use yii\db\Schema;
use mata\user\migrations\Migration;

class m150428_140312_addpk extends Migration
{
    public function safeUp() {
        $this->addPrimaryKey("PK_DocumentId_Revision", "{{%arhistory_revision}}", ["DocumentId", "Revision"]);
    }

    public function safeDown() {
    }
}