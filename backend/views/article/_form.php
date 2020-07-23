<?php

use backend\models\Article;
use backend\models\Tag;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\ArticleCategory;
use backend\models\Game;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $form yii\widgets\ActiveForm */

if ($model->production_status === null) {
    $model->production_status = Article::PRODUCTION_STATUS__DEFAULT;
}
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'avatar_image_id')
                        ->dropDownList(($image = $model->avatarImage) ? [$image->id => $image->name] : []) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'extra_image_id')
                        ->dropDownList(($image = $model->extraImage) ? [$image->id => $image->name] : []) ?>
                </div>
            </div>

            <?= $form->field($model, 'article_category_id')
                ->dropDownList(ArticleCategory::dropDownListData(), ['prompt' => '']) ?>

            <?= $form->field($model, 'published_time')->textInput() ?>

            <?= $form->field($model, 'production_status')->dropDownList(Article::$allProductionStatusLabels) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'heading')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'page_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'meta_keywords')->textarea(['maxlength' => true]) ?>

            <?= $form->field($model, 'meta_description')->textarea(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'active')->checkbox() ?>

            <?= $form->field($model, 'visible')->checkbox() ?>

            <?= $form->field($model, 'featured')->checkbox() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'allow_indexing')->checkbox() ?>

            <?= $form->field($model, 'allow_following')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'video_src')->textInput(['maxlength' => true]) ?>

            <?php
            $relatedArticles = Article::find()->where(['IN', 'id', $model->related_article_ids])->all();
            $tags = Tag::find()->where(['IN', 'id', $model->tag_ids])->all();
            ?>

            <?= $form->field($model, 'related_article_ids')
                ->dropDownList(ArrayHelper::map($relatedArticles, 'id', 'name'), ['multiple' => true]) ?>

            <div style="display: none">
                <?= $form->field($model, 'tag_ids')
                    ->dropDownList(ArrayHelper::map($tags, 'id', 'name'), ['multiple' => true]) ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    // TODO: Show/hide inputs for special type of chosen article category
    <?php
    $video_category_ids = [];
    $product_category_ids = [];
    foreach (ArticleCategory::indexData() as $category) {
        switch ($category->type) {
            case ArticleCategory::TYPE_VIDEO:
                $video_category_ids[] = $category->id;
                break;
            case ArticleCategory::TYPE_PRODUCT:
                $product_category_ids[] = $category->id;
                break;
            case ArticleCategory::TYPE_NEWS:
                break;
        }
    }
    ?>
    /**
     * @var {integer[]} video_category_ids
     * @var {integer[]} product_category_ids
     */
    var video_category_ids = <?= json_encode($video_category_ids) ?>;
    var product_category_ids = <?= json_encode($product_category_ids) ?>;
    var video_input_wrappers = [
        document.querySelector('.form-group.field-<?= Html::getInputId($model, 'video_src') ?>')
    ];
    var product_input_wrappers = [
        document.querySelector('.form-group.field-<?= Html::getInputId($model, 'production_status') ?>'),
        document.querySelector('.form-group.field-<?= Html::getInputId($model, 'extra_image_id') ?>')
    ];

    var video_inputs_init = function () {

    };

    var product_inputs_init_done = false;
    var product_inputs_init = function () {
        if (!product_inputs_init_done) {
            window.initPortableImageUploader("<?= Html::getInputId($model, 'extra_image_id') ?>");
            product_inputs_init_done = true;
        }
    };

    var category_id_input = document.querySelector('#<?= Html::getInputId($model, 'article_category_id') ?>');
    var updateInputDisplays = function () {
        var category_id = Number.parseInt(category_id_input.value);
        video_input_wrappers.concat(product_input_wrappers).forEach(function (input_wrapper) {
            input_wrapper.style.display = 'none';
        });
        if (video_category_ids.indexOf(category_id) > -1) {
            video_input_wrappers.forEach(function (input_wrapper) {
                input_wrapper.style.display = '';
            });
            video_inputs_init();
        }
        if (product_category_ids.indexOf(category_id) > -1) {
            product_input_wrappers.forEach(function (input_wrapper) {
                input_wrapper.style.display = '';
            });
            product_inputs_init();
        }
    };
    category_id_input.addEventListener('change', updateInputDisplays);
    updateInputDisplays();
</script>

<script>
    initCKEditor("<?= Html::getInputId($model, 'content') ?>");
    initDatetimePicker("<?= Html::getInputId($model, 'published_time') ?>");
</script>

<?php
$this->registerJs(
    '
    initPortableImageUploader("' . Html::getInputId($model, 'avatar_image_id') . '");
    //initPortableImageUploader("' . Html::getInputId($model, 'extra_image_id') . '");
    
    initRemoteDropDownList("'
        . Html::getInputId($model, 'tag_ids') . '", "'
        . Url::to(['api/find-tags'])
    . '", true);
    
    initRemoteDropDownList("'
        . Html::getInputId($model, 'related_article_ids') . '", "'
        . Url::to(['api/find-articles'])
    . '", true);
    
    ',
    \yii\web\View::POS_READY
);
