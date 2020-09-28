<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Upload Settings';
?>
<div class="site-settings site-form row">
    <div class="box-styles">
        <h1 class="admin-headings"><?= Html::encode($this->title) ?></h1>
        <div class="settings-content">
            <?php $form = ActiveForm::begin(['id' => 'settings-form','action'=>'/index.php?r=site%2Fupdatesettings']); ?>
            <?php foreach($model as $setting): ?>
                <div class="form-group field-settings">
                    <label class="control-label" for="settings-<?= $setting['name'] ?>"><?= $setting['label'] ?></label>
                    <input type="text" id="settings-<?= $setting['name'] ?>" class="form-control" value="<?= $setting['value'] ?>" name="<?= $setting['name'] ?>" autofocus="">
                    <p class="help-block help-block-error"></p>
                </div>
            <?php endforeach ?>
            <div class="form-group submit-btn-container">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary submit-btn', 'name' => 'submit-button']) ?>
            </div>
            <?= Html::a('Back',['manage-users'],['class' => 'label label-info back-btn']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
