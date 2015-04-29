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
class m150428_140312_addpk extends Migration
{
    public function up() {
        $this->addPrimaryKey("PK_DocumentId_Revision", "{{%arhistory_revision}}", ["DocumentId", "Revision"]);
    }

    public function down() {
    }
}