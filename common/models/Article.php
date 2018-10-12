<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\web\UrlManager;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $heading
 * @property string $page_title
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $description
 * @property string $video_src
 * @property string $content
 * @property int $active
 * @property int $visible
 * @property int $featured
 * @property int $allow_indexing
 * @property int $allow_following
 * @property int $view_count
 * @property string $published_time
 * @property string $created_time
 * @property string $updated_time
 * @property int $creator_id
 * @property int $updater_id
 * @property int $article_category_id
 * @property int $avatar_image_id
 * @property int $extra_image_id
 * @property int $production_status
 *
 * @property ArticleCategory $articleCategory
 * @property Image $avatarImage
 * @property User $creator
 * @property User $updater
 * @property Image $extraImage
 * @property ArticleToRelatedArticle[] $articleToRelatedArticles
 * @property Article[] $relatedArticles
 * @property ArticleToTag[] $articleToTags
 * @property Tag[] $tags
 * @property MenuItem[] $menuItems
 */
class Article extends \common\db\MyActiveRecord
{
    const PRODUCTION_STATUS__DONE = 1;
    const PRODUCTION_STATUS__DOING = 2;
    const PRODUCTION_STATUS__DEFAULT = self::PRODUCTION_STATUS__DONE;

    /**
     * @var string[]
     */
    public static $allProductionStatusLabels = [
        self::PRODUCTION_STATUS__DONE => 'Đã hoàn thiện',
        self::PRODUCTION_STATUS__DOING => 'Đang triển khai',
    ];

    /**
     * @return string
     */
    public function productionStatusLabel() {
        if (isset(self::$allProductionStatusLabels[$this->production_status])) {
            return self::$allProductionStatusLabels[$this->production_status];
        }

        return self::$allProductionStatusLabels[self::PRODUCTION_STATUS__DEFAULT];
    }

    /**
     * @var string[]
     */
    public static $allProductionStatusAliases = [
        self::PRODUCTION_STATUS__DONE => 'done',
        self::PRODUCTION_STATUS__DOING => 'doing',
    ];

    /**
     * @return string
     */
    public function productionStatusAlias() {
        if (isset(self::$allProductionStatusAliases[$this->production_status])) {
            return self::$allProductionStatusAliases[$this->production_status];
        }

        return self::$allProductionStatusAliases[self::PRODUCTION_STATUS__DEFAULT];
    }

    /**
     * @param $params array
     * @param bool $schema
     * @return string
     */
    public function viewUrl($params = [], $schema = true)
    {
        if (Yii::$app->params['amp']) {
            $params[UrlParam::AMP] = 'amp';
        }

        /**
         * @var $urlMng UrlManager
         */
        $urlMng = Yii::$app->frontendUrlManager;

        return ($schema ? Yii::getAlias('@frontendHost') : '') . $urlMng->createUrl(
            array_merge(['article/view', UrlParam::SLUG => $this->slug], $params)
        );
    }

    /**
     * @param string|null $text
     * @param array $options
     * @return string
     */
    public function viewAnchor($text = null, $options = [])
    {
        if (!isset($options['title'])) {
            $options['title'] = $this->name;
        }
        return Html::a($text === null ? $this->name : $text, $this->viewUrl(), $options);
    }

    /**
     * @param string|null $size
     * @param array $options
     * @return string
     */
    public function avatarImg($size = null, $options = [])
    {
        if ($this->avatarImage) {
            return $this->avatarImage->img($size, $options);
        }
        return '';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'creator_id',
                'updatedByAttribute' => 'updater_id',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_time',
                'updatedAtAttribute' => 'updated_time',
                'value' => (new \DateTime())->format('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'description', 'content', 'published_time', 'article_category_id', 'avatar_image_id'], 'required'],
            [['content'], 'string'],
            [['published_time'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['active', 'visible', 'featured', 'allow_indexing', 'allow_following', 'avatar_image_id', 'extra_image_id', 'article_category_id', 'production_status'], 'integer'],
            [['name', 'slug', 'heading', 'page_title', 'meta_title'], 'string', 'max' => 255],
            [['meta_keywords', 'meta_description', 'description', 'video_src'], 'string', 'max' => 511],
            [['slug'], 'unique'],
            [['article_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCategory::className(), 'targetAttribute' => ['article_category_id' => 'id']],
            [['avatar_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['avatar_image_id' => 'id']],
            [['extra_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['extra_image_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'heading' => 'Heading',
            'page_title' => 'Page Title',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'description' => 'Description',
            'video_src' => 'Video Src',
            'content' => 'Content',
            'active' => 'Active',
            'visible' => 'Visible',
            'featured' => 'Featured',
            'allow_indexing' => 'Allow Indexing',
            'allow_following' => 'Allow Following',
            'view_count' => 'View Count',
            'published_time' => 'Published Time',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'creator_id' => 'Creator ID',
            'updater_id' => 'Updater ID',
            'article_category_id' => 'Article Category ID',
            'avatar_image_id' => 'Avatar Image ID',
            'extra_image_id' => 'Extra Image ID',
            'production_status' => 'Production Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleCategory()
    {
        return $this->hasOne(ArticleCategory::className(), ['id' => 'article_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvatarImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'avatar_image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtraImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'extra_image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updater_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleToRelatedArticles()
    {
        return $this->hasMany(ArticleToRelatedArticle::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedArticles()
    {
        return $this->hasMany(Article::className(), ['id' => 'related_article_id'])->viaTable('article_to_related_article', ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleToTags()
    {
        return $this->hasMany(ArticleToTag::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('article_to_tag', ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['article_id' => 'id']);
    }
}
