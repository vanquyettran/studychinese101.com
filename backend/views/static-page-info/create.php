<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\StaticPageInfo */

$this->title = 'Create Static Page Info';
$this->params['breadcrumbs'][] = ['label' => 'Static Page Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-page-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
