<?php

namespace startpl\t2cmsblog\backend;

class Module extends \yii\base\Module
{
    const MODULE_NAME = "nsblog";
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'startpl\t2cmsblog\backend\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        
        \Yii::configure($this, require __DIR__ . '/config.php');
        $this->registerTranslations();
    }
    
    private function registerTranslations()
    {
        if (!isset(\Yii::$app->i18n->translations[self::MODULE_NAME . '*'])) {
            
            \Yii::$app->i18n->translations[self::MODULE_NAME . '*'] = [
                'class'    => \yii\i18n\PhpMessageSource::class,
                'basePath' => dirname(__DIR__) . '/messages',
                'fileMap'  => [
                    self::MODULE_NAME . "/error" => "error.php", 
                ],
            ];
        }
    }
}
