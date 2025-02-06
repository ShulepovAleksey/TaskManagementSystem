<?php

use modules\task\models\Task\Task;
use modules\task\models\Task\TaskStatus;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var \modules\task\models\Task\Task $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить эту задачу?',
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
                    /** @var $model Task */
                    return $model->getTaskState()->getStatus()->getStatusName();
                }
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
