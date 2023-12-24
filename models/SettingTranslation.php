<?php

namespace andrylik\settings\models;

use Yii;

/**
 * This is the model class for table "setting_translation".
 *
 * @property int $model_id
 * @property string $language
 * @property string $value
 */
class SettingTranslation extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return '{{%setting_translation}}';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['model_id'], 'integer'],
			[['value'], 'string'],
			[['language'], 'string', 'max' => 5],
			[['model_id', 'language'], 'unique', 'targetAttribute' => ['model_id', 'language']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'value' => Yii::t('andrylik.settings', 'Value') . ' [' . $this->language . ']',
		];
	}
}