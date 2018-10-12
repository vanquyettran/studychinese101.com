<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\StaticPageInfo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Static Page Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-page-info-view">

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
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'route',
            'url_pattern:url',
            'heading',
            'page_title',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'long_description:ntext',
            'active',
            'allow_indexing',
            'allow_following',
            'avatar_image_id',
        ],
    ]) ?>

</div>
