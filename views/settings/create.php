<?php

/* @var $this \yii\web\View */
/* @var $model \andrylik\settings\models\SettingModel */

$this->title = Yii::t('andrylik.settings', 'Create Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('andrylik.settings', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid setting-create">

    <?php echo $this->render('_form', ['model' => $model]);?>

</div>
