<?php
/**
 * View Count plugin for Craft CMS
 *
 * Count the number of times an element has been viewed.
 *
 * @author    Double Secret Agency
 * @link      https://www.doublesecretagency.com/
 * @copyright Copyright (c) 2019 Double Secret Agency
 */

namespace doublesecretagency\viewcount\migrations;

use craft\db\Migration;

/**
 * Installation Migration
 * @since 1.0.0
 */
class Install extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTables();
        $this->createIndexes();
        $this->addForeignKeys();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTableIfExists('{{%viewcount_elementtotals}}');
        $this->dropTableIfExists('{{%viewcount_viewlog}}');
        $this->dropTableIfExists('{{%viewcount_userhistories}}');
    }

    /**
     * Creates the tables.
     */
    protected function createTables()
    {
        $this->createTable('{{%viewcount_elementtotals}}', [
            'id'          => $this->primaryKey(),
            'elementId'   => $this->integer()->notNull(),
            'viewKey'     => $this->string(),
            'viewTotal'   => $this->integer()->defaultValue(0),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid'         => $this->uid(),
        ]);
        $this->createTable('{{%viewcount_viewlog}}', [
            'id'          => $this->primaryKey(),
            'elementId'   => $this->integer()->notNull(),
            'viewKey'     => $this->string(),
            'userId'      => $this->integer(),
            'ipAddress'   => $this->string(),
            'userAgent'   => $this->text(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid'         => $this->uid(),
        ]);
        $this->createTable('{{%viewcount_userhistories}}', [
            'id'          => $this->integer()->notNull(),
            'history'     => $this->text(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid'         => $this->uid(),
            'PRIMARY KEY([[id]])',
        ]);
    }

    /**
     * Creates the indexes.
     */
    protected function createIndexes()
    {
        $this->createIndex(null, '{{%viewcount_elementtotals}}', ['elementId']);
        $this->createIndex(null, '{{%viewcount_viewlog}}',       ['elementId']);
    }

    /**
     * Adds the foreign keys.
     *
     * @return void
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey(null, '{{%viewcount_elementtotals}}', ['elementId'], '{{%elements}}', ['id'], 'CASCADE');
        $this->addForeignKey(null, '{{%viewcount_viewlog}}',       ['elementId'], '{{%elements}}', ['id'], 'CASCADE');
        $this->addForeignKey(null, '{{%viewcount_userhistories}}', ['id'],        '{{%users}}',    ['id'], 'CASCADE');
    }

}
