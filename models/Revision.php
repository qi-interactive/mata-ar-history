<?php

namespace mata\arhistory\models;

use Yii;
use mata\behaviors\BlameableBehavior;
use mata\behaviors\IncrementalBehavior;
/**
 * This is the model class for table "{{%mata_arhistory_revision}}".
 *
 * @property string $DocumentId
 * @property integer $Revision
 * @property string $DateCreated
 * @property string $Attributes
 * @property integer $CreatedBy
 */
class Revision extends \mata\db\ActiveRecord {
    
    public function behaviors() {
       return [
               [
                   'class' => BlameableBehavior::className(),
                   'createdByAttribute' => 'CreatedBy',
                   'updatedByAttribute' => false,
               ],
               [
                    'class' => IncrementalBehavior::className(),
                    'findBy' => "DocumentId",
                    'incrementField' => "Revision"
               ]
           ];
    }

    public static function tableName()
    {
        return '{{%arhistory_revision}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DocumentId', 'Revision', 'Attributes', 'CreatedBy'], 'required'],
            [['Revision', 'CreatedBy'], 'integer'],
            [['DateCreated', 'Status'], 'safe'],
            [['Attributes'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DocumentId' => 'Document ID',
            'Revision' => 'Revision',
            'DateCreated' => 'Date Created',
            'Attributes' => 'Attributes',
            'CreatedBy' => 'Created By',
        ];
    }
}