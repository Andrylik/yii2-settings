<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
    </a>
    <h1 align="center">Yii2 Settings Extension</h1>
    <br>
</p>

Settings Manager for Yii2 with the possibility of translating values.

Installation
------------

Via [Composer](http://getcomposer.org/download/).

```sh
php composer.phar require --prefer-dist andrylik/yii2-settings "*"
```
**Database Migrations**

Before usage this extension, we'll also need to prepare the database.

```sh
php yii migrate --migrationPath=@vendor/andrylik/yii2-settings/migrations
```


Configuration
-------------
**Module Setup**

Configure "Yii2 Settings Extension" module in ```backend/config/main.php```:

```php
'modules' => [
    'settings' => [
        'class' => 'andrylik\settings\Module',
    ],
],
```

If you need to translate the values to other languages

add parameters in ```common/config/params.php```

```php
return [
    // ...
    'languages' => ['uk', 'ru', 'en'], //languages to translate
    'defaultLanguage' => 'uk' //default app language
];
```

Also specify the language of the application ```common/config/main.php```

```php
return [
    // ...
    'language' => 'uk',
    //..
];
```


**Component Setup**

Configure Settings Component ```common/config/main.php```
```php
'components' => [
    'cache' => [
        'class' => \yii\caching\FileCache::class,
        'cachePath' => '@frontend/runtime/cache'
    ],
    'settings' => [
        'class' => 'andrylik\settings\components\Settings',
    ],
],
```

Usage:
---------

Go to ```http://backend.yourdomain.com/settings``` for managing your settings

Use the settings in your application

```php
$settings = Yii::$app->settings;

$value = $settings->get('section', 'key');
```

