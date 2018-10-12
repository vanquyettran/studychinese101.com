<?php
/**
 * Created by PhpStorm.
 * User: Quyet
 * Date: 4/24/2018
 * Time: 2:44 PM
 *
 * @var $parentCategory frontend\models\ArticleCategory
 * @var $categories frontend\models\ArticleCategory[]
 */
use frontend\controllers\ArticleController;

?>
<section class="article-category">
    <div class="body clr">
        <div class="shares">
            <?= $this->render('//layouts/likeShare') ?>
        </div>
            <?php
            if ($parentCategory->introduction) {
                ?>
                <div class="long-desc expandable-wrapper">
                    <div class="expandable-content expandable">
                        <div class="paragraph">
                            <?= $parentCategory->introduction ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>

            <?php
            foreach ($categories as $category) {
                $articles = ArticleController::findModels($category->getAllArticles(), 1, 5);
                if (count($articles) > 0) {
                ?>
                    <div class="content-list">
                        <div class="heading">
                            <?= $category->viewAnchor(null, ['class' => 'text-content']) ?>
                        </div>
                        <div class="body">
                            <div class="product-thumbnail-list aspect-ratio __1x1 clr">
                                <ul class="clr">
                                    <?= $this->render('_thumbnailList', [
                                        'models' => $articles,
                                        'imageSize' => '340x340',
                                    ]) ?>
                                </ul>
                            </div>
                            <div class="view-all">
                                <?= $category->viewAnchor('Xem tất cả &raquo;', ['class' => 'view-all-link']) ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
    </div>
</section>