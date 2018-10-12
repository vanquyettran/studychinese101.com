<?php
/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\Article
 * @var $modelType string
 * @var $relatedItems \frontend\models\Article[]
 */
use frontend\models\ArticleCategory;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$addToBreadcrumb = function (\common\models\ArticleCategory $category) use (&$addToBreadcrumb) {
    if ($category->parent) {
        $addToBreadcrumb($category->parent);
    }
    $this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => $category->viewUrl()];
};
if ($model->articleCategory) {
    $addToBreadcrumb($model->articleCategory);
}
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => $model->viewUrl()];
?>
    <?= $this->render('//layouts/breadcrumb', [
        'links' => $this->params['breadcrumbs'],
    ]) ?>
    <div class="article-detail">
        <h1 class="name"><?= Html::encode($model->name) ?></h1>
        <div class="info">
            <?= $this->render('_info', compact('model')) ?>
        </div>
        <?php
        if ($model->description !== '') {
            ?>
            <div class="intro">
                <p><?= str_replace("\n", "</p><p>", Html::encode($model->description)) ?></p>
            </div>
            <?php
        }
        ?>
        <div class="top-shares">
            <?= $this->render('//layouts/likeShare') ?>
        </div>
        <?php
        switch ($modelType) {
            case ArticleCategory::TYPE_NEWS:
                break;
            case ArticleCategory::TYPE_VIDEO:
                ?>
                <div class="video-container">
                    <div class="video aspect-ratio __16x9">
                        <div>
                            <iframe src="<?= $model->video_src ?>" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <?php
                break;
        }
        ?>
        <div class="content paragraph">
            <?= $model->content ?>
        </div>
        <div class="bottom-shares">
            <?= $this->render('//layouts/likeShare') ?>
        </div>
        <div class="comments">
            <?= $this->render('//layouts/fbComment') ?>
        </div>
    </div>

    <?php
    if (count($relatedItems) > 0) {
        ?>
        <section class="article-related">
            <!--<div class="heading">
                <div class="text-content">Xem thêm</div>
            </div>-->
            <div class="body">
                <?php
                $category = $model->articleCategory;
                if ($category && $category->type === ArticleCategory::TYPE_PRODUCT) {
                    ?>
                    <div class="product-thumbnail-list aspect-ratio __1x1 clr">
                        <?= $this->render('_thumbnailList', [
                            'models' => $relatedItems,
                            'imageSize' => '340x340'
                        ]) ?>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="news-thumbnail-list aspect-ratio __3x2 clr">
                        <?= $this->render('_thumbnailList', [
                            'models' => $relatedItems,
                            'imageSize' => '300x200'
                        ]) ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>
        <?php
    }
    ?>

<script>
    window.addEventListener("load", function () {
        updateArticleCounter(<?= $model->id ?>, "view_count", 1);
    });
</script>