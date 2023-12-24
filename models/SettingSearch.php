<?php

namespace andrylik\settings\models;

use Yii;
use yii\data\ActiveDataProvider;
use andrylik\settings\models\SettingModel;
use yii\helpers\ArrayHelper;

/**
 * Class SettingSearch
 *
 * @package andrylik\settings\models
 */
class SettingSearch extends SettingModel
{
    /**
     * @var int the default page size
     */
    public $pageSize = 30;

    const STATUS_TRANSLATED = 1;
    const STATUS_NOT_TRANSLATED = 2;

    /**
     * @var int
     */
    public $is_tpanslated;

    public static function getTranslateStatusesArray()
    {
        return [
            self::STATUS_TRANSLATED => Yii::t('andrylik.settings', 'Translated'),
            self::STATUS_NOT_TRANSLATED => Yii::t('andrylik.settings', 'Not translated'),           
        ];
    }

    public function getTranslateStatusName()
    {
        return ArrayHelper::getValue(self::getTranslateStatusesArray(), $this->is_tpanslated);
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['section', 'key', 'value', 'status', 'description', 'is_tpanslated'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->pageSize,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'section' => $this->section,
            'status' => $this->status,
        ]);

        if ($this->is_tpanslated == static::STATUS_TRANSLATED) {
            $query->translated();
        }
        if ($this->is_tpanslated == static::STATUS_NOT_TRANSLATED) {
            $query->notTranslated();
        }

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    } 
    
}
