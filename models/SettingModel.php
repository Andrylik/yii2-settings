<?php

namespace andrylik\settings\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use creocoder\translateable\TranslateableBehavior;
use andrylik\settings\Module;

/**
 * This is the model class for table "{{%setting}}".
 *
 * @property int $id
 * @property string $section
 * @property string $key
 * @property string $value
 * @property bool $status
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class SettingModel extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;    
    
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['section', 'key', 'value'], 'required'],
            [['section', 'key'], 'unique', 'targetAttribute' => ['section', 'key']],
            [['value'], 'string'],
            [['section', 'key', 'description'], 'string', 'max' => 255],
            [['status'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        if (Module::isTranslatable()){
            $valueLabel = Yii::t('andrylik.settings', 'Value') . ' [' . Yii::$app->params['defaultLanguage'] . ']';
        }
        else{
            $valueLabel = Yii::t('andrylik.settings', 'Value');
        }

        return [
            'id' => Yii::t('andrylik.settings', 'ID'),            
            'section' => Yii::t('andrylik.settings', 'Section'),
            'key' => Yii::t('andrylik.settings', 'Key'),
            'value' => $valueLabel,
            'status' => Yii::t('andrylik.settings', 'Status'),
            'description' => Yii::t('andrylik.settings', 'Description'),
            'created_at' => Yii::t('andrylik.settings', 'Created Date'),
            'updated_at' => Yii::t('andrylik.settings', 'Updated Date'),
        ];
    }

    public static function getStatusesArray()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('andrylik.settings', 'Active'),
            self::STATUS_INACTIVE => Yii::t('andrylik.settings', 'Inactive'),            
        ];
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }    

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            'translateable' => [
                'class' => TranslateableBehavior::class,
                'translationAttributes' => ['value'],
            ],
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(SettingTranslation::class, ['model_id' => 'id']);
    }


    /**
     * Creates an [[ActiveQueryInterface]] instance for query purpose.
     *
     * @return SettingQuery
     */
    public static function find(): SettingQuery
    {
        return new SettingQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        SettingTranslation::deleteAll(['model_id' => $this->id]);

        Yii::$app->cache->delete('andrylik-setting');
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);        

        Yii::$app->cache->delete('andrylik-setting');
    }

    /**
     * Return array of settings
     *
     * @return array
     */
    public function getSettings(): array
    {
        $result = [];
        
        $settings = static::find()->with('translations')->active()->all();

        foreach ($settings as $setting) {
            $section = $setting->section;
            $key = $setting->key;
            $lang = Yii::$app->language;
            $value = $setting->value;

            $result[$section][$key][$lang] = $value;

            if (Module::isTranslatable()){
                
                foreach(Yii::$app->params['languages'] as $language){
                    if ($setting->hasTranslation($language) && $setting->translate($language)->value){
                        $result[$section][$key][$language] = $setting->translate($language)->value;
                    }else{
                        $result[$section][$key][$language] = $value;
                    }                    
                }
            }
        }

        return $result;
    }

    /**
     * Return count languages to translate
     * 
     * @return int
     */
    public static function getCountLanguagesToTranslate()
    {
        if (Module::isTranslatable()){
            $translatedLanguages = Yii::$app->params['languages'];
            ArrayHelper::removeValue($translatedLanguages, Yii::$app->params['defaultLanguage']);
            return count($translatedLanguages);
        }
        return false;
    }

    /**
     * Return array id translated values
     * 
     * @return array
     */
    public static function getTranslatedId()
    {
        $res = [];
        $translatedLanguages = self::getCountLanguagesToTranslate();

        $translated = SettingTranslation::find()
            ->select('model_id')
            ->where(['!=', 'value', ''])
            ->groupBy(['model_id'])
            ->having('count(*) = ' . $translatedLanguages)
            ->asArray()
            ->all();

        if($translated){
            foreach($translated as $key => $value){
                $res[] = $value['model_id'];
            }
        }
        
        return $res;
    }
    
    /**
     * Check is model translated
     *
     * @param int $modelId
     * @return bool
     */
    public static function isModelFullyTranslated($modelId)
    {   
        if (Module::isTranslatable()){
            return SettingTranslation::find()
                ->where(['model_id' => $modelId])
                ->andWhere(['!=', 'value', ''])
                ->count() == self::getCountLanguagesToTranslate();
        }
        return false;
    }
    
}
