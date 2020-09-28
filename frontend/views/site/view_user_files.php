<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'View User Files';
?>
<div class="view-files-wrapper">
    <div class="box-styles site-view-user-files">
        <h1 class="admin-headings"><?= Html::encode($this->title) ?></h1>
        <div class="files-container">
            <?php foreach (explode(';',$user_files) as $item): ?>
                <?php $path = '/img/temp/' . $user_id . DIRECTORY_SEPARATOR . $item; ?>
                <p><span class="bold_text">File Name:</span><a href="<?= $path ?>"> <?= $item ?></a></p>
            <?php endforeach; ?>
        </div>
        <?= Html::a('Back',['manage-users'],['class' => 'label label-info back-btn']) ?>
    </div>
</div>