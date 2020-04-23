<?php

namespace startpl\t2cmsblog\models;

use Yii;
use t2cms\sitemanager\models\{
    Domain,
    Language
};

/**
 * This is the model class for table "{{%page_content}}".
 *
 * @property int $id
 * @property int $src_id
 * @property int|null $domain_id
 * @property int|null $language_id
 * @property string $name
 * @property string $h1
 * @property string $image
 * @property string $preview_text
 * @property string $full_text
 * @property string $title
 * @property string $og_title
 * @property string $keywords
 * @property string $description
 * @property string $og_description
 *
 * @property Page $page
 * @property Domain $domain
 * @property Language $language
 */
class PageContent extends \yii\db\ActiveRecord
{
    use \t2cms\base\traits\ContentValueTrait;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_content}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['src_id', 'name', 'h1', 'image', 'preview_text', 'full_text', 'title', 'og_title', 'keywords', 'description', 'og_description'], 'required'],
            [['src_id', 'domain_id', 'language_id'], 'integer'],
            [['preview_text', 'full_text', 'description', 'og_description'], 'string'],
            [['name', 'h1', 'image', 'title', 'og_title', 'keywords'], 'string', 'max' => 255],
            [['src_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['src_id' => 'id']],
            [['domain_id'], 'exist', 'skipOnError' => true, 'targetClass' => Domain::className(), 'targetAttribute' => ['domain_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'src_id' => Yii::t('app', 'Page ID'),
            'domain_id' => Yii::t('app', 'Domain ID'),
            'language_id' => Yii::t('app', 'Language ID'),
            'name' => Yii::t('app', 'Name'),
            'h1' => Yii::t('app', 'H1'),
            'image' => Yii::t('app', 'Image'),
            'preview_text' => Yii::t('app', 'Preview Text'),
            'full_text' => Yii::t('app', 'Full Text'),
            'title' => Yii::t('app', 'Title'),
            'og_title' => Yii::t('app', 'Og Title'),
            'keywords' => Yii::t('app', 'Keywords'),
            'description' => Yii::t('app', 'Description'),
            'og_description' => Yii::t('app', 'Og Description'),
        ];
    }

    /**
     * Gets query for [[Page]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'src_id']);
    }

    /**
     * Gets query for [[Domain]].
     *
     * @return \yii\db\ActiveQuery|DomainQuery
     */
    public function getDomain()
    {
        return $this->hasOne(Domain::className(), ['id' => 'domain_id']);
    }

    /**
     * Gets query for [[Language]].
     *
     * @return \yii\db\ActiveQuery|LanguageQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }
}
