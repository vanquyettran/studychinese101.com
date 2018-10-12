<?php

use backend\models\ArticleCategory;
use backend\models\MenuItem;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MenuItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-6">
        <?= $form->field($model, 'menu_id')->dropDownList($model->getMenuNames()) ?>

        <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sort_order')->textInput() ?>

        <?= $form->field($model, 'parent_id')->dropDownList(
            MenuItem::dropDownListData($model->isNewRecord ? [] : [$model->id]),
            ['prompt' => '']
        ) ?>

        <!--
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'anchor_target')->dropDownList([
                    '_self' => '_self',
                    '_parent' => '_parent',
                    '_top' => '_top',
                    '_blank' => '_blank',
                ], ['prompt' => '']) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'anchor_rel')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        -->
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'static_page_route')->dropDownList($model->getStaticPageRouteLabels(), ['prompt' => '']) ?>

        <?= $form->field($model, 'article_category_id')->dropDownList(ArticleCategory::dropDownListData(), ['prompt' => '']) ?>

        <?= $form->field($model, 'article_id')->dropDownList(
            $model->article ? [$model->article->id => $model->article->name] : [],
            ['prompt' => '']
        ) ?>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(
    '
    initRemoteDropDownList("'
        . Html::getInputId($model, 'article_id') . '", "'
        . Url::to(['api/find-articles']) . '");
    ',
    \yii\web\View::POS_READY
);
