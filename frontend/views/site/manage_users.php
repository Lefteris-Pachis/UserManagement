<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Manage Users';
?>
<div class="site-manage-users">
    <p>In this page the Administrator can manage the users.</p>
    <div class="table-wrapper">
        <h1 class="admin-headings"><?= Html::encode($this->title) ?></h1>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Status</th>
                <th scope="col">Created At</th>
                <th scope="col">Updated At</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user): ?>
                <tr class="table-primary">
                    <th scope="row" data-label="Id"><?= $user->id ?></th>
                    <td data-label="Username"><?= $user->username ?></td>
                    <td data-label="Email"><?= $user->email ?></td>
                    <td data-label="Status"><?= ($user->status == 10) ? 'Activated' : 'Deactivated' ?></td>
                    <td data-label="Created At"><?= date("d/m/Y H:i:s",$user->created_at) ?></td>
                    <td data-label="Updated At"><?= date("d/m/Y H:i:s",$user->updated_at) ?></td>
                    <td>
                        <?php if ($user->files_counter > 0): ?>
                            <?= Html::a('View User Files',['view-user-files', 'id' => $user->id],['class' => 'label label-info']) ?>
                        <?php endif; ?>
                        <?php if ($user->status == 9): ?>
                            <?= Html::a('Activate User',['activate', 'id' => $user->id],['class' => 'label label-success']) ?>
                        <?php else: ?>
                            <?= Html::a('Deactivate User',['deactivate', 'id' => $user->id],['class' => 'label label-warning']) ?>
                        <?php endif; ?>
                        <?= Html::a('Delete User',['delete', 'id' => $user->id],['class' => 'label label-danger'] ) ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <p>Number of users: <?= count($users) ?></p>
</div>