<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace mata\arhistory\actions;

use mata\arhistory\models\Revision;

class HistoryAction extends \yii\base\Action {

	/**
	 * @var string the view file to be rendered. If not set, it will take the value of [[id]].
	 * That means, if you name the action as "history" in "SiteController", then the view name
	 * would be "history", and the corresponding view file would be "views/site/history.php".
	 */
	public $view;

	public function run($documentId) {

		$revisions = Revision::find()->where([
			"DocumentId" => $documentId
			])->orderBy("Revision DESC")->all();

		if(\Yii::$app->request->isAjax) {
			return $this->controller->renderAjax($this->view ?: $this->id, [
				"revisions" => $revisions
			]);
		} else {
			return $this->controller->render($this->view ?: $this->id, [
				"revisions" => $revisions
			]);
		}
	}
}  
