<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use andrylik\settings\Module;

/* @var $this \yii\web\View */
/* @var $model \andrylik\settings\models\SettingModel */
?>
<style>.help-block{color:#dc3545;}.form-group {margin-bottom: 1rem;}</style>
<div class="setting-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-6">
            <?php echo $form->field($model, 'section')->textInput(['maxlength' => 255]); ?>
        </div>
        <div class="col-6">
            <?php echo $form->field($model, 'key')->textInput(['maxlength' => 255]); ?>
        </div>
        <div class="col-12">
            <?php echo $form->field($model, 'value')->textarea(['rows' => 3]); ?>
        </div>
    </div>

    <? if( Module::isTranslatable() ):?>
        <div class="mb-3 rounded border border-dark">            
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <p class="mt-2 font-weight-bold"><?=Yii::t('andrylik.settings', 'If the value does not require translation, you can leave the field empty')?></p>
                    </div>
                    <? foreach (Yii::$app->params['languages'] as $language): ?>
                        <? if ( $language === Yii::$app->params['defaultLanguage'] ): ?>
                            <? continue;   // Exclude the defaultLanguage ?> 
                        <? endif; ?>
                        <div class="col-12">
                            <?= $form->field($model->translate($language), "[$language]value")->textarea(['rows' => 3]); ?>
                        </div>     
                    <? endforeach; ?>
                </div>
            </div>            
        </div>
    <? endif; ?>
    
    <div class="row">
        <div class="col-12">
            <?php echo $form->field($model, 'description')->textarea(['rows' => 5]); ?>
        </div>
        <div class="col-12">
            <?php echo $form->field($model, 'status')->dropDownList($model::getStatusesArray()); ?>
        </div>
    </div>
    
    <div class="form-group pb-3">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('andrylik.settings', 'Create') : Yii::t('andrylik.settings', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        <?php echo Html::a(Yii::t('andrylik.settings', 'Go Back'), ['index'], ['class' => 'btn btn-default']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
