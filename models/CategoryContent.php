<?php

namespace startpl\t2cmsblog\models;

use Yii;
use t2cms\sitemanager\models\{
    Domain,
    Language
};

/**
 * This is the model class for table "{{%category_content}}".
 *
 * @property int $id
 * @property int $src_id
 * @property int $domain_id
 * @property int $language_id
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
 * @property string $og_url
 * @property string $og_sitename
 * @property string $og_type
 *
 * @property Category $category
 * @property Domain $domain
 * @property Language $language
 */
class CategoryContent extends \yii\db\ActiveRecord
{
    use \t2cms\base\traits\ContentValueTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category_content}}';
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
            [['name', 'h1', 'image', 'title', 'og_title', 'keywords', 'og_url', 'og_sitename', 'og_type'], 'string', 'max' => 255],
            [['src_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['src_id' => 'id']],
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
            'src_id' => Yii::t('app', 'Category ID'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'src_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDomain()
    {
        return $this->hasOne(Domain::className(), ['id' => 'domain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }
}
