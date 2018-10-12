<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\searchModels\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Articles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Article', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'slug',
            [
                'attribute' => 'article_category_id',
                'value' => function (\backend\models\Article $model) {
                    if ($category = $model->articleCategory) {
                        return $category->name;
                    }
                    return null;
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'article_category_id',
                    \backend\models\ArticleCategory::dropDownListData(),
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            'active:boolean',
            'visible:boolean',
            'featured:boolean',
            'allow_indexing:boolean',
            'allow_following:boolean',
            'published_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
