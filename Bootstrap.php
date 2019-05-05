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

		Event::on(ActiveQuery::class, ActiveQuery::EVENT_BEFORE_PREPARE_STATEMENT, function(Event $event) {

			$activeQuery = $event->sender;
			$modelClass = $activeQuery->modelClass;
			$sampleModelObject = new $modelClass;

			if (BehaviorHelper::hasBehavior($sampleModelObject, \mata\arhistory\behaviors\HistoryBehavior::class)) {
				$documentIdBase = $sampleModelObject->getDocumentId()->getId();
				$tableAlias = $activeQuery->getQueryTableName($activeQuery);
				$tableAlias = is_string($tableAlias[0]) ? $tableAlias[0] : $tableAlias[1];

				if (count($modelClass::primaryKey()) > 1) {
					throw new HttpException(500, sprintf("Composite keys are not handled yet. Table alias is %s", $tableAlias));
				}

				$tablePrimaryKey = $modelClass::primaryKey()[0];
				$this->addArhistoryJoin($activeQuery, "CONCAT('" . $documentIdBase . "', " . $tableAlias . "." . $tablePrimaryKey . ")", $documentIdBase);
			}
		});
	}

	private function addArhistoryJoin($activeQuery, $documentId, $documentIdBase) {

		$module = \Yii::$app->getModule("environment");

		if ($module == null)
			throw new \yii\base\InvalidConfigException("'environment' module pointing to matacms\\environment\\Module module needs to be set");

		/**
		 * We need to check if a given [[documentId]] is present in the [[ItemEnvironment]] table.
		 * If is it not, it means that environments are not used for a given [[documentId]]
		 * This check cannot be done with [[BehaviorHelper::hasBehavior]], as we not always have
		 * the model class name, but always have the [[documentId]]
		 */

        $hasVersions = \Yii::$app->cache->get(__CLASS__ . "_has_records_" . md5($documentIdBase));

        if ($hasVersions === false) {
            $hasVersions = Revision::find()->where(['like', 'DocumentId', $documentIdBase])->limit(1)->one();
            \Yii::$app->cache->set(__CLASS__ . "_has_records_" . md5($documentIdBase), $hasVersions);
        }

		if ($hasVersions == null)
			return;

		$alias = $this->getTableAlias();

		// TODO This encoding happens in Yii, use what they're offering. E.g. it is used in the call on line 91
		$documentId = str_replace("\\", "\\\\",  $documentId);

		if ($activeQuery->select == null)
			$activeQuery->addSelect(["*", $alias . ".Revision"]);
		else
			$activeQuery->addSelect($alias . ".Revision");

		$activeQuery->innerJoin("arhistory_revision " . $alias, "`" . $alias . "`.`DocumentId` = " . $documentId . " AND " .
				$alias . ".Revision = " . $alias . ".Revision AND " . $alias . ".Status = 1");
		$activeQuery->andWhere($alias . ".Revision = (SELECT Revision FROM arhistory_revision " . $alias . "rev WHERE " . $alias . "rev.`DocumentId` = " . $alias . ".DocumentId
			 	 		 			 ORDER BY Revision DESC LIMIT 1)");
	}

	private function getTableAlias() {
		return "history" . ++self::$envQueryCount;
	}
}
