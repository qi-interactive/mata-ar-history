<?php

/**
* @link http://www.matacms.com/
* @copyright Copyright (c) 2015 Qi Interactive Limited
* @license http://www.matacms.com/license/
*/

namespace mata\arhistory\behaviors;

use Yii;
use yii\base\Event;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use mata\arhistory\models\Revision;
use yii\web\ServerErrorHttpException;
use mata\base\MessageEvent;
use mata\helpers\BehaviorHelper;
use yii\log\Logger;

class HistoryBehavior extends Behavior
{

    const EVENT_REVISION_FETCHED = "EVENT_REVISION_FETCHED";
    public $_revision;
    public $_createVersion = true;
    private $getRevisionAfterFind = true;
    /**
    * ;
    */
    public $Status = 1;

    public function events()
    {

        $events = [
            BaseActiveRecord::EVENT_AFTER_INSERT => "afterSave",
            BaseActiveRecord::EVENT_AFTER_UPDATE => "afterSave",
            BaseActiveRecord::EVENT_AFTER_DELETE => "afterDelete",
            BaseActiveRecord::EVENT_AFTER_FIND => "afterFind"
        ];

        return $events;
    }

    public function afterFind(Event $event)
    {

        if (!is_a(Yii::$app, "yii\console\Application") && $this->getRevisionAfterFind && Yii::$app->user->isGuest == false) {
            if (\Yii::$app->request->get('revision') != null) {
                $this->setRevision(\Yii::$app->request->get('revision'));
                $revision = $this->getRevision();
            } else {
                $revision = $this->getLatestRevision();
            }

            $this->setAttributesFromRevision($event, $revision);
        } else {
            $revision = $this->getRevision();
            $this->setAttributesFromRevision($event, $revision);
        }

        Event::trigger(self::class, self::EVENT_REVISION_FETCHED, new MessageEvent($this->owner));
    }

    public function afterSave(Event $event)
    {

        $model = $event->sender;

        if ($model->_createVersion == false || BehaviorHelper::hasBehavior($model, \mata\arhistory\behaviors\HistoryBehavior::class) == false) {
            return;
        }

        $revision = new Revision();
        $revision->attributes = [
            "DocumentId" => $model->getDocumentId()->getId(),
            "Attributes" => serialize($model->attributes),
            "Status" => $this->Status
        ];

        if ($revision->save() == false) {
            throw new ServerErrorHttpException($revision->getTopError());
        }
    }

    public function afterDelete(Event $event)
    {
    }

    public function setRevision($revision)
    {
        $revision = Revision::find()->where([
            "DocumentId" => $this->owner->getDocumentId()->getId(),
            "Revision" => $revision
            ])->one();

        if ($revision) {
            $this->owner->setAttributes($this->owner->attributes, false);
            $this->owner->_revision = $revision;
        }
    }

    public function noRevision()
    {
        $this->getRevisionAfterFind = false;
    }

    public function getRevision()
    {
        return $this->owner->_revision;
    }

    public function getLatestRevision()
    {

        $documentId = $this->owner->getDocumentId()->getId();
        return Revision::find()->where([
            "DocumentId" => $documentId
            ])->orderBy('Revision DESC')->limit(1)->one();
    }

    public function disableVersioning()
    {
        $this->owner->_createVersion = false;
    }

    private function setAttributesFromRevision($event, $revision)
    {
        if ($revision != null) {
            /**
            * We cannot do $event->sender->attributes = unserialize($revision->Attributes) as if
            * attribute no longer exists on the table it will throw an exception
            **/
            try {
                $attributes = unserialize($revision->Attributes);

                foreach ($attributes as $attribute => $value) {
                    if ($event->sender->hasAttribute($attribute)) {
                        $event->sender->$attribute = $value;
                    }
                }
    
                $this->owner->_revision = $revision;
            } catch (\yii\base\ErrorException $e) {
                /**
                 * If data cannot be deserialised, the original version will be returned 
                 * and a warning will be issued in to the application log.
                 */
                \Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_WARNING);
            }
        }
    }
}
