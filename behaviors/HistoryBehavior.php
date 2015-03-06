<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace mata\arhistory\behaviors;

use Yii;
use yii\base\Event;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use mata\arhistory\models\Revision;
use yii\web\ServerErrorHttpException;

class HistoryBehavior extends Behavior {

    public function events() {

        return YII_DEBUG ? [] : 
           [
               BaseActiveRecord::EVENT_AFTER_FIND => "afterFind",
               BaseActiveRecord::EVENT_AFTER_INSERT => "afterSave",
               BaseActiveRecord::EVENT_AFTER_UPDATE => "afterSave",
               BaseActiveRecord::EVENT_AFTER_DELETE => "afterDelete"
           ];
   }

   public function afterFind(Event $event) {
    $revision = $this->getLatestRevision($event->sender);

    if ($revision != null)
        $event->sender->attributes = unserialize($revision->Attributes);

}

public function afterSave(Event $event) {

    $model = $event->sender;

    $revision = new Revision();
    $revision->attributes = [
    "DocumentId" => $model->getDocumentId(),
    "Attributes" => serialize($model->attributes),
    "Status" => 0
    ];

    if ($revision->save() == false)
        throw new ServerErrorHttpException($revision->getTopError());
}

public function afterDelete(Event $event) {

}

private function getLatestRevision(BaseActiveRecord $model) {
    $documentId = $model->getDocumentId();
    return Revision::find()->where([
        "DocumentId" => $documentId
        ])->orderBy('Revision DESC')->one();
}
}