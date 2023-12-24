<?php

namespace andrylik\settings\components;

use Yii;
use yii\base\Component;
use yii\caching\Cache;
use yii\di\Instance;

/**
 * Class Settings
 *
 * @package andrylik\settings\components
 */
class Settings extends Component
{
    /**
     * @var string setting model class name
     */
    public $modelClass = 'andrylik\settings\models\SettingModel';

    /**
     * @var Cache|array|string the cache used to improve RBAC performance. This can be one of the followings:
     *
     * - an application component ID (e.g. `cache`)
     * - a configuration array
     * - a [[yii\caching\Cache]] object
     *
     * When this is not set, it means caching is not enabled
     */
    public $cache = 'cache';

    /**
     * @var string the key used to store settings data in cache
     */
    public $cacheKey = 'andrylik-setting';

    /**
     * @var \andrylik\settings\models\SettingModel setting model
     */
    protected $model;

    /**
     * @var array list of settings
     */
    protected $items;

    /**
     * @var mixed setting value
     */
    protected $setting;

    /**
     * Initialize the component
     */
    public function init()
    {
        parent::init();

        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, Cache::class);
        }

        $this->model = Yii::createObject($this->modelClass);
    }    

    /**
     * Get's the value for the given section and key.
     *
     * @param string $section
     * @param string $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($section, $key, $default = null)
    {
        $items = $this->getSettingsConfig();
        $language = Yii::$app->language;
        
        if (isset($items[$section][$key])) {           
            $this->setting = $items[$section][$key][$language];        
        } else {
            $this->setting = $default;
        }
        
        return $this->setting;
    }    

    /**
     * Returns the settings config
     *
     * @return array
     */
    protected function getSettingsConfig(): array
    {
        if (!$this->cache instanceof Cache) {
            $this->items = $this->model->getSettings();
        } else {
            $cacheItems = $this->cache->get($this->cacheKey);
            if (!empty($cacheItems)) {
                $this->items = $cacheItems;
            } else {
                $this->items = $this->model->getSettings();
                $this->cache->set($this->cacheKey, $this->items);
            }
        }

        return $this->items;
    }    
}
