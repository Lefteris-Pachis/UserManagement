<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
?>
<div class="site-form site-signup row">
    <div class="form-container col-lg-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>Please fill out the following fields to signup:</p>
        <div>
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Username']) ?>
            <?= $form->field($model, 'email')->input('email', ['placeholder' => 'Email']) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password']) ?>
            <div class="form-group">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary submit-btn', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>