<?php

namespace startpl\t2cmsblog\models;

use Yii;

/**
 * This is the model class for table "{{%additional_page}}".
 *
 * @property int $id
 * @property int $resource_id
 * @property int $page_id
 * @property int $type 0 - additional page, 1 - related page
 * @property int $source_type 0 - category assign page, 1 - page assign page
 *
 */
class PageAssign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_assign}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resource_id', 'page_id', 'type', 'source_type'], 'required'],
            [['resource_id', 'page_id', 'type', 'source_type'], 'integer'],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'resource_id' => Yii::t('app', 'Parent ID'),
            'page_id' => Yii::t('app', 'Child ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelated()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'resource_id']);
    }
}
