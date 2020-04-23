<?php

namespace startpl\t2cmsblog\models;

/**
 * This is the ActiveQuery class for [[Page]].
 *
 * @see Page
 */
class PageQuery extends \yii\db\ActiveQuery
{
    public function behaviors() 
    {
        return [
            'content' => [
                'class' => \t2cms\base\behaviors\ContentBehavior::className(),
                'relationName' => 'pageContent',
                'relationModel' => PageContent::className()
            ]
        ];
    }

    /**
     * {@inheritdoc}
     * @return Page[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Page|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
