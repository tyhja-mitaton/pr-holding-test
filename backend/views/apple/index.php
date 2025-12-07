<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $apples common\models\Apple[] */

$this->title = 'Яблоки';
?>
<div class="apple-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('warning')): ?>
        <div class="alert alert-warning">
            <?= Yii::$app->session->getFlash('warning') ?>
        </div>
    <?php endif; ?>


    <div class="mb-3">
        <?= Html::a('Сгенерировать яблоки', ['generate'], [
            'class' => 'btn btn-success',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <?php if (empty($apples)): ?>
        <p>На данный момент яблок нет.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Цвет</th>
                <th>Состояние</th>
                <th>Осталось (Размер)</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($apples as $apple): ?>
                <tr>
                    <td><?= $apple->id ?></td>
                    <td style="background-color: <?= Html::encode($apple->color) ?>;"><?= Html::encode($apple->color) ?></td>
                    <td>
                        <?php if ($apple->isRotten()): ?>
                            <span class="badge badge-danger">Гнилое</span>
                        <?php elseif ($apple->on_tree): ?>
                            <span class="badge badge-primary">На дереве</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">На земле</span>
                        <?php endif; ?>
                    </td>
                    <td><?= round($apple->size * 100) ?>%</td>
                    <td>
                        <?php if ($apple->on_tree): ?>
                            <?= Html::a('Упасть', ['fall', 'id' => $apple->id], [
                                'class' => 'btn btn-warning btn-sm',
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => 'Вы уверены, что хотите сбросить яблоко?',
                                ],
                            ]) ?>
                        <?php else: ?>
                            <?php if (!$apple->isRotten()): ?>
                                <?php $form = ActiveForm::begin([
                                    'action' => ['eat', 'id' => $apple->id],
                                    'method' => 'post',
                                    'options' => ['class' => 'form-inline']
                                ]); ?>
                                <div class="form-group mr-2">
                                    <input type="number" name="percent" class="form-control form-control-sm" value="10" min="1" max="100" style="width: 80px;">
                                </div>
                                <?= Html::submitButton('Съесть %', ['class' => 'btn btn-info btn-sm']) ?>
                                <?php ActiveForm::end(); ?>
                            <?php else: ?>
                                <button class="btn btn-sm btn-danger" disabled>Сгнило</button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
