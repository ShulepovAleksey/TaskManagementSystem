<?php

use app\models\Task\TaskStatus;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Task\Task $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php if ($model->canChangeStatus(TaskStatus::InProcess->value)): ?>
            <?= Html::a(
                'Перевести в статус \'В процессе\'',
                ['change-status', 'id' => $model->id, 'newStatusId' => TaskStatus::InProcess->value],
                ['class' => 'btn btn-secondary']
            ) ?>
        <?php endif; ?>
        <?php if ($model->canChangeStatus(TaskStatus::Finished->value)): ?>
            <?= Html::a(
                'Перевести в статус \'Завершена\'',
                ['change-status', 'id' => $model->id, 'newStatusId' => TaskStatus::Finished->value],
                ['class' => 'btn btn-secondary']
            ) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'description:ntext',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return TaskStatus::getStatusById($model->status)->getStatusName();
                }
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
