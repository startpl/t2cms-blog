<?php

namespace startpl\t2cmsblog\models;

use Yii;

/**
 * This is the model class for table "{{%additional_category}}".
 *
 * @property int $id
 * @property int $resource_id
 * @property int $category_id
 * @property int $type 0 - additional category, 1 - related category
 * @property int $source_type 0 - category assign category, 1 - category assign page
 *
 * @property Category $related
 * @property Category $category
 */
class CategoryAssign extends \yii\db\ActiveRecord
{
    const ASSIGN = ['CATEGORY' => 0, 'PAGE' => 1];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category_assign}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resource_id', 'category_id', 'type', 'source_type'], 'required'],
            [['resource_id', 'category_id', 'type', 'source_type'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['resource_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['resource_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'resource_id' => Yii::t('app', 'Category ID'),
            'category_id' => Yii::t('app', 'Related ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelated()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'resource_id']);
    }
}
