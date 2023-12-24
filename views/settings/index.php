<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use andrylik\settings\models\SettingModel;
use andrylik\settings\models\SettingSearch;

/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel \andrylik\settings\models\SettingSearch */

$this->title = Yii::t('andrylik.settings', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid setting-index">
    

    <p><?php echo Html::a(Yii::t('andrylik.settings', 'Create Setting'), ['create'], ['class' => 'btn btn-success']); ?></p>
    
    <?php echo GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pager' => [
                'class' => 'yii\bootstrap5\LinkPager',
            ],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                ],
                [
                    'attribute' => 'section',
                    'filter' => ArrayHelper::map(SettingModel::find()->select('section')->distinct()->all(), 'section', 'section'),
                ],
                'key',
                'value:ntext',                
                'description:ntext',
                [
                    'attribute' => 'status',
                    "format" => "raw",
                    'filter' => SettingModel::getStatusesArray(),
                    'value' => function($model){                
                        $model->status ? $class = 'success' : $class = 'warning';                    
                        return Html::tag('span', Html::encode($model->statusName), ['class' => 'badge bg-' . $class]);
                    }                    
                ],
                [
                    'attribute' => 'is_tpanslated',
                    'label' => Yii::t('andrylik.settings', 'Translate'),
                    "format" => "raw",
                    'value' => function ($model) {
                        if ($model::isModelFullyTranslated($model->id)){
                            $class = 'success';
                            $text = Yii::t('andrylik.settings', 'Translated');
                        }
                        else {
                            $class = 'warning';
                            $text = Yii::t('andrylik.settings', 'Not translated');
                        }
                        return Html::tag('span', $text, ['class' => 'badge bg-' . $class]);
                    },
                    'filter' => SettingSearch::getTranslateStatusesArray(),
                    'visible' => Yii::$app->controller->module->isTranslatable()
                ],
                [
                    'header' => Yii::t('andrylik.settings', 'Actions'),
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} &nbsp; {delete}',
                ],                
            ],
        ]
    ); ?>
</div>
