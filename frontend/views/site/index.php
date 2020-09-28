<?php
use common\models\Settings;
use yii\bootstrap\ActiveForm;
use frontend\models\UploadForm;
use dosamigos\fileupload\FileUploadUI;
/* @var $this yii\web\View */
$model = new UploadForm();
$file_size = Settings::findByName('file_size')->value;
$file_types = Settings::findByName('file_types')->value;
if($file_types){
    $ft_array = explode(",",$file_types);
    foreach ($ft_array as $key => $value){
        $ft_array[$key] = '.'.$value;
    }
    $file_types = implode(",",$ft_array);
}else{
    $file_types = '*/*';
}
$this->title = 'User Management';
?>
<div class="site-index">
        <?php $form = ActiveForm::begin(); ?>
        <?= FileUploadUI::widget([
            'model' => $model,
            'attribute' => 'file',
            'url' => ['site/upload'],
            'gallery' => false,
            'fieldOptions' => [
                'accept' => $file_types
            ],
            'clientOptions' => [
                'maxFileSize' => ($file_size * 1000000)
            ],
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
                'fileuploadfail' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
            ],
        ]); ?>
        <?php ActiveForm::end(); ?>
</div>
