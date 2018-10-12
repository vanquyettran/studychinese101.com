<?php
/**
 * @var $this \yii\web\View
 * @var $category ArticleCategory
 * @var $articles \frontend\models\Article[]
 * @var $queryParams string
 * @var $hasMore boolean
 * @var $page integer
 */
use frontend\models\ArticleCategory;
use yii\helpers\Html;

$viewId = '_thumbnailList';
$imageSize = '300x200';
if ($category->type === ArticleCategory::TYPE_PRODUCT) {
    $imageSize = '340x340';
}
?>
<section class="article-category">
    <div class="body clr">
        <div class="shares">
            <?= $this->render('//layouts/likeShare') ?>
        </div>
        <?php
        if ($category->introduction) {
            ?>
            <div class="long-desc expandable-wrapper">
                <div class="expandable-content expandable">
                    <div class="paragraph">
                        <?= $category->introduction ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="content-list">
            <?php
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
                <?php
                if (count($articles) > 0) {
                    echo $this->render($viewId, [
                        'models' => $articles,
                        'imageSize' => $imageSize
                    ]);
                } else {
                    echo 'Chưa có nội dung cho mục này.';
                }
                ?>
            </div>
            <?php
            if ($hasMore) {
                ?>
                <button
                    type="button"
                    class="see-more-button"
                    onclick="loadMore(this.previousElementSibling, this)"
                >Xem thêm</button>
                <?php
            }
            ?>
        </div>
    </div>
</section>

<script>
    var viewId = "<?= $viewId ?>";
    var viewParams = {
        imageSize: "<?= $imageSize ?>"
    };
    var queryParams = <?= json_encode($queryParams) ?>;
    var nextPage = <?= $page + 1 ?>;
    function loadMore(container, button) {
        loadMoreArticles(container, button, viewId, viewParams, queryParams, nextPage, function () {
            nextPage++;
        });
    }
</script>
