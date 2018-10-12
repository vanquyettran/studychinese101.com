<?php

use yii\db\Migration;

/**
 * Handles adding extra_image_id to table `article`.
 * Has foreign keys to the tables:
 *
 * - `image`
 */
class m181010_174733_add_extra_image_id_column_to_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('article', 'extra_image_id', $this->integer()->after('avatar_image_id'));

        // creates index for column `extra_image_id`
        $this->createIndex(
            'idx-article-extra_image_id',
            'article',
            'extra_image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-article-extra_image_id',
            'article',
            'extra_image_id',
            'image',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `image`
        $this->dropForeignKey(
            'fk-article-extra_image_id',
            'article'
        );

        // drops index for column `extra_image_id`
        $this->dropIndex(
            'idx-article-extra_image_id',
            'article'
        );

        $this->dropColumn('article', 'extra_image_id');
    }
}
