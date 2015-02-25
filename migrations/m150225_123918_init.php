<?php

/*
 * This file is part of the mata project.
 *
 * (c) mata project <http://github.com/mata/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use yii\db\Schema;
use mata\user\migrations\Migration;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class m150225_123918_init extends Migration
{
    public function up()
    {
        $this->createTable('{{%arhistory_revision}}', [
            'DocumentId'   => Schema::TYPE_STRING . '(64) NOT NULL',
            'Revision'     => Schema::TYPE_INTEGER . ' NOT NULL',
            'DateCreated'  => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'Attributes'   => 'longtext NOT NULL',
            'CreatedBy'    => Schema::TYPE_INTEGER .'(11) UNSIGNED NOT NULL',
            'Status'       => 'tinyint(1) NOT NULL',
            'Comment'      => Schema::TYPE_TEXT
        ]);

    }

    public function down() {
        $this->dropTable('{{%arhistory_revision}}');
    }
}