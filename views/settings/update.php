<?php

/* @var $this \yii\web\View */
/* @var $model \andrylik\settings\models\SettingModel */

$this->title = Yii::t('andrylik.settings', 'Update Setting: {0} -> {1}', [$model->section, $model->key]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('andrylik.settings', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('andrylik.settings', 'Update Setting');
?>
<div class="container-fluid setting-update">

    <?php echo $this->render('_form', ['model' => $model]);?>

</div>
