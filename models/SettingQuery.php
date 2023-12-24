<?php

namespace andrylik\settings\models;

use yii\db\ActiveQuery;
use andrylik\settings\models\SettingModel;

/**
 * Class SettingQuery
 *
 * @package andrylik\settings\models
 */
class SettingQuery extends ActiveQuery
{
    /**
     * Scope for settings with active status
     *
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['status' => SettingModel::STATUS_ACTIVE]);
    }

    /**
     * Scope for settings with inactive status
     *
     * @return $this
     */
    public function inactive()
    {
        return $this->andWhere(['status' => SettingModel::STATUS_INACTIVE]);
    }

    /**
     * Appends condition for not translated messages
     *
     * @return $this
     */
    public function notTranslated()
    {
        $settingTableName = SettingModel::tableName();
        $translatedIds = SettingModel::getTranslatedId();
        $this->andWhere(['not in', "$settingTableName.id", $translatedIds]);
        return $this;
    }

    /**
     * Appends condition for translated messages
     *
     * @return $this
     */
    public function translated()
    {
        $settingTableName = SettingModel::tableName();
        $translatedIds = SettingModel::getTranslatedId();
        $this->andWhere(['in', "$settingTableName.id", $translatedIds]);
        return $this;
    }
}
