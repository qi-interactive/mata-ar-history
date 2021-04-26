<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace mata\arhistory;

use Yii;
use yii\base\Event;
use mata\base\MessageEvent;
use mata\arhistory\behaviors\HistoryBehavior;
use mata\helpers\BehaviorHelper;
use matacms\controllers\module\Controller;
use matacms\environment\models\ItemEnvironment;
use matacms\environment\Module;
use yii\db\BaseActiveRecord;
use mata\db\ActiveQuery;
use yii\web\HttpException;
use mata\arhistory\models\Revision;

class Bootstrap extends \mata\base\Bootstrap {

        private static $envQueryCount = -1;

        public function bootstrap($app) {
                return false;
        }
}
