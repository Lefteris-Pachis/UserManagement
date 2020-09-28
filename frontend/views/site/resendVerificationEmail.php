<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Resend verification email';
?>
<div class="site-resend-verification-email site-form row">
    <div class="form-container col-lg-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>Please fill out your email. A verification email will be sent there.</p>
        <div>
            <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>
            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Email']) ?>
            <div class="form-group">
                <?= Html::submitButton('Send', ['class' => 'btn btn-primary submit-btn']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
