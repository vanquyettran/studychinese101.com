<?php

use common\models\ArticleCategory;
use frontend\controllers\ArticleController;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 */

?>

<div class="products-by-categories">
    <?php
    foreach (ArticleCategory::indexData(true) as $category) {
        if (!$category->parent_id) {
            $child_categories = $category->findChildren();
            ?>
                <div class="heading">
                    <?= $category->viewAnchor(null, ['class' => 'text-content']) ?>
                </div>
                <div class="body">
                    <?php
                    if (count($child_categories) > 0) {
                        foreach ($child_categories as $child_category) {
                            $articles = ArticleController::findModels($child_category->getAllArticles(), 1, 5);
                            if (count($articles) > 0) {
                                ?>
                                <div class="child-heading">
                                    <?= $child_category->viewAnchor(null, ['class' => 'text-content']) ?>
                                </div>
                                <div class="child-body">
                                    <div class="product-thumbnail-list aspect-ratio __1x1 clr">
                                        <ul class="clr">
                                            <?= $this->render('//article/_thumbnailList', [
                                                'models' => $articles,
                                                'imageSize' => '340x340'
                                            ]) ?>
                                        </ul>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    } else {
                        $articles = ArticleController::findModels($category->getAllArticles(), 1, 5);

                        if ($category->type === ArticleCategory::TYPE_PRODUCT) {
                            ?>
                            <div class="product-thumbnail-list aspect-ratio __1x1 clr">
                            <?php
                        } else {
                            ?>
                            <div class="news-thumbnail-list aspect-ratio __3x2 clr">
                            <?php
                        }
                        ?>
                            <ul class="clr">
                                <?= $this->render('//article/_thumbnailList', [
                                    'models' => $articles,
                                    'imageSize' => $category->type === ArticleCategory::TYPE_PRODUCT
                                        ? '340x340' : '300x200'
                                ]) ?>
                            </ul>
                        </div>
                        <?php
                        }
                    ?>
                </div>
            <?php
        }
    }
    ?>
</div>
