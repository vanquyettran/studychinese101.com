<?php
/**
 * @var $this \yii\web\View
 * @var $models \frontend\models\Article[]
 */

use common\models\ArticleCategory;
use yii\helpers\Html;

if (!isset($imageSize)) {
    $imageSize = null;
}

foreach ($models as $model) {
    $isProduct = false;
    foreach (ArticleCategory::indexData() as $category) {
        if ($category->id === $model->article_category_id) {
            $isProduct = $category->type === ArticleCategory::TYPE_PRODUCT;
            break;
        }
    }
    ?>
    <li>
        <?= $model->viewAnchor(
            '<div class="image"><div>'
            . $model->avatarImg($imageSize)
            . '</div></div>'

            . '<h3 class="name" data-max-line-count="3">'
            . Html::encode($model->name)
            . '</h3>'

            . '<div class="info">'
            . $this->render('_info', compact('model'))
            . '</div>'

            . (!$isProduct
                ? (
                    '<div class="intro" data-max-line-count="3">'
                    . '<p>' . str_replace("\n", '</p><p>', Html::encode($model->description)) . '</p>'
                    . '</div>')
                : '')
            ,
            [
                'class' => 'clr'
            ]
        ); ?>
    </li>
    <?php
}
