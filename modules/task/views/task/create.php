<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \modules\task\models\Task\Task $model */

$this->title = 'Добавить задачу';
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
