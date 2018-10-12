<?php

use yii\db\Migration;

/**
 * Handles adding production_status to table `article`.
 */
class m181006_093408_add_production_status_column_to_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('article', 'production_status', $this->smallInteger());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('article', 'production_status');
    }
}
