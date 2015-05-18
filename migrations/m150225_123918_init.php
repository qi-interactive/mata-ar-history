<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

use yii\db\Schema;
use mata\user\migrations\Migration;

class m150225_123918_init extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%arhistory_revision}}', [
            'DocumentId'   => Schema::TYPE_STRING . '(64) NOT NULL',
            'Revision'     => Schema::TYPE_INTEGER . ' NOT NULL',
            'DateCreated'  => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'Attributes'   => 'longtext NOT NULL',
            'CreatedBy'    => Schema::TYPE_INTEGER .'(11) UNSIGNED NOT NULL'
        ]);

    }

    public function safeDown() {
        $this->dropTable('{{%arhistory_revision}}');
    }
}
