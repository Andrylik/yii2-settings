<?php

namespace andrylik\settings;
use Yii;
use yii\base\InvalidConfigException;

/**
 * settings module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'andrylik\settings\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        Yii::$app->i18n->translations['andrylik.settings'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'basePath' => '@andrylik/settings/messages',
        ];

        if (self::isTranslatable() && Yii::$app->params['defaultLanguage'] === null) {
            throw new InvalidConfigException("The 'defaultLanguage' property must be set - For Advanced Application file /common/config/params.php");
        }
    }

    /**
     * Check languages to translate
     * 
     * @return bool
     */
    public static function isTranslatable(){
        if (isset(Yii::$app->params['languages']) && count(Yii::$app->params['languages']) > 1){
            return true;
        }
        else{
            return false;
        }
    }
}
