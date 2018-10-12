<?php
/**
 * @var $model \frontend\models\Article
 */

use common\models\ArticleCategory;

$isProduct = false;
foreach (ArticleCategory::indexData() as $category) {
    if ($category->id === $model->article_category_id) {
        $isProduct = $category->type === ArticleCategory::TYPE_PRODUCT;
        break;
    }
}

if ($isProduct) {
    ?>
    <div class="production-status production-status-<?= $model->productionStatusAlias() ?>">
    <i class="icon <?= $model->productionStatusAlias() ?>-icon"></i>
    <span><?= $model->productionStatusLabel() ?></span>
    </div>
    <?php
} else {
    ?>
    <span>
        <i class="icon calendar-icon"></i>
        <span><?= (new \DateTime($model->published_time))->format('d/m/Y') ?></span>
    </span>
    <span>
        <i class="icon eye-icon"></i>
        <span><?= $model->view_count ?> xem</span>
    </span>
    <?php
}
?>

