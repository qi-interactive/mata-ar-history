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
use \mata\base\MessageEvent;

class HistoryBehavior extends Behavior {

  const EVENT_REVISION_FETCHED = "EVENT_REVISION_FETCHED";

  public function events() {

    $events = [ 
      BaseActiveRecord::EVENT_AFTER_INSERT => "afterSave",
      BaseActiveRecord::EVENT_AFTER_UPDATE => "afterSave",
      BaseActiveRecord::EVENT_AFTER_DELETE => "afterDelete"
      ];

    if (is_a(Yii::$app, "matacms\web\Application") == false)
      $events[] = [BaseActiveRecord::EVENT_AFTER_FIND => "afterFind"];

    return $events;
  }

  public function afterFind(Event $event) {
    $revision = $this->getLatestRevision();

    if ($revision != null)
      $event->sender->attributes = unserialize($revision->Attributes);

    Event::trigger(self::class, self::EVENT_REVISION_FETCHED, new MessageEvent($this->owner));

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

  public function setRevision($revision) {
    $revision = Revision::find()->where([
      "DocumentId" => $this->owner->getDocumentId(),
      "Revision" => $revision
      ])->one();

    if ($revision)
      $this->owner->attributes = unserialize($revision->Attributes);
  }

  public function getLatestRevision() {

    $documentId = $this->owner->getDocumentId();
    return Revision::find()->where([
      "DocumentId" => $documentId
      ])->orderBy('Revision DESC')->one();
  }
}