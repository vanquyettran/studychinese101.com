<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\models\Banner;
use common\models\SiteParam;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);

/**
 * @var $seoInfo \frontend\models\SeoInfo
 */
$seoInfo = $this->context->seoInfo;
$seoInfo->registerMetaTags($this);
$seoInfo->registerLinkTags($this);

$this->title = $seoInfo->page_title ? $seoInfo->page_title : Yii::$app->name;

$hasPageHeadline = in_array(Yii::$app->requestedRoute, ['site/index', 'article/category']);

$regCss = function (...$cssFiles) {
    foreach ($cssFiles as $cssFile) {
        echo str_replace(
            ['@img'],
            [Yii::getAlias('@web/img')],
            file_get_contents(Yii::getAlias("@webroot/css/$cssFile.css"))
        );
    }
};

/**
 * @var Banner[] $headerBanners
 */
$headerBanners = $this->context->headerBanners;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="<?= $hasPageHeadline ? 'has-page-headline' : '' ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
    <style><?php $regCss('shared', 'icons', 'separated', 'slider'); ?></style>
    <?= $this->render('//layouts/headerJs') ?>
</head>
<body data-sticky-container="global">
<?php $this->beginBody() ?>
    <div id="menu-mobile-backdrop"
         onclick="document.querySelector('html').classList.remove('menu-active')">
    </div>

    <?php
    if ($hasPageHeadline) {
        ?>
        <div id="page-headline"">
            <div class="container clr">
                <h1 class="content"><?= $seoInfo->heading ?></h1>
            </div>
        </div>
        <?php
    }
    ?>

    <div id="header-overlay" data-sticky-in="global" data-sticky-responsive="mobile|tablet">
        <div id="header">
            <div class="container">
                <a class="text-logo" href="<?= Url::home(true) ?>" title="<?= Yii::$app->name ?>">
                    <span class="text"><?= Yii::$app->name ?></span>
                </a>
            </div>
        </div>
        <?= $this->render('//layouts/topNav', ['menu' => $this->context->menu]) ?>
    </div>


    <?php
    if (count($headerBanners) > 0) {
        ?>
        <div id="big-banner">
            <div class="slider"
                 data-item-aspect-ratio="3"
                 data-autorun-delay="3000"
                 data-slide-time="300"
                 data-slide-timing="ease"
                 data-swipe-timing="ease-out"
                 data-display-navigator="true"
            >
                <?php
                foreach ($headerBanners as $banner) {
                    /**
                     * @var $banner Banner
                     */
                    if ($banner->image) {
                        ?>
                        <div class="image aspect-ratio __3x1">
                            <div>
                                <?= $banner->image->img() ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="container clr" id="main-content">
        <?= $content ?>
    </div>

    <?= $this->render('//layouts/bottomDesc') ?>
    <?= $this->render('//layouts/footer') ?>
    <?= $this->render('//layouts/footerJs') ?>

    <?= $this->render('//layouts/fbSDK') ?>
    <?= $this->render('//layouts/googlePlatform') ?>
    <?= $this->render('//layouts/twitterWidget') ?>
    <?= $this->render('//layouts/tracking') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
