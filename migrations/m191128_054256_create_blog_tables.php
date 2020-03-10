<?php

use yii\db\Migration;

/**
 * Class m191128_054256_create_blog_tables
 */
class m191128_054256_create_blog_tables extends Migration
{
    const PUBLISH     = 2;
    const ACCESS_READ = 1;
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try{
            $this->createCategory();
            $this->createPage();
            $this->createRelations();

            $this->fillData();
        } catch( \Exception $e){
            $this->safeDown();
            die("Something went wrong");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%related_page}}');
        $this->dropTable('{{%related_category}}');
        $this->dropTable('{{%additional_page}}');
        $this->dropTable('{{%additional_category}}');
        
        $this->dropTable('{{%meta_blog_category}}');
        $this->dropTable('{{%meta_blog_page}}');
        $this->dropTable('{{%page}}');
        $this->dropTable('{{%category}}');
        return true;
    }
    
    private function createCategory()
    {
        $this->createTable('{{%category}}', [
            'id'                => $this->primaryKey(),
            'url'               => $this->string(255)->notNull(),
            'author_id'         => $this->integer()->notNull(),
            'status'            => $this->integer(2)->notNull(),
            'tree'              => $this->integer(),
            'lft'               => $this->integer()->notNull(),
            'rgt'               => $this->integer()->notNull(),
            'depth'             => $this->integer()->notNull(),
            'parent_id'         => $this->integer(),
            'position'          => $this->integer()->notNull()->defaultValue(0),
            'access_read'       => $this->integer()->notNull(),
            'records_per_page'  => $this->integer()->notNull()->defaultValue(15),
            'sort'              => $this->string(255),
            'main_template'     => $this->string(255),
            'category_template' => $this->string(255),
            'page_template'     => $this->string(255),
            'publish_at'        => $this->dateTime()->notNull(),
            'created_at'        => $this->dateTime()->notNull(),
            'updated_at'        => $this->dateTime()->notNull(),
        ]);
        
        $this->createTable('{{%category_content}}', [
            'id'             => $this->primaryKey(),
            'category_id'    => $this->integer()->notNull(),
            'domain_id'      => $this->integer(),
            'language_id'    => $this->integer(),
            'name'           => $this->string(255)->notNull(),
            'h1'             => $this->string(255)->notNull(),
            'image'          => $this->string(255)->notNull(),
            'preview_text'   => $this->text()->notNull(),
            'full_text'      => $this->text()->notNull(),
            'title'          => $this->string(255)->notNull(),
            'og_title'       => $this->string(255)->notNull(),
            'keywords'       => $this->string(255)->notNull(),
            'description'    => $this->text()->notNull(),
            'og_description' => $this->text()->notNull(),
        ]);
        
        $this->addForeignKey('fk-content_category-category_id', '{{%category_content}}', 'category_id', '{{%category}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-content_category-domain_id', '{{%category_content}}', 'domain_id', '{{%domain}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-content_category-language_id', '{{%category_content}}', 'language_id', '{{%language}}', 'id', 'CASCADE');
        
    }
    
    private function createPage()
    {
        $this->createTable('{{%page}}', [
            'id'         => $this->primaryKey(),
            'url'        => $this->string(255)->notNull(),
            'author_id'  => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'status'      => $this->integer(2)->notNull(),
            'position'   => $this->integer()->notNull()->defaultValue(0),
            'access_read' => $this->integer()->notNull(),
            'main_template' => $this->string(255),
            'page_template' => $this->string(255),
            'publish_at' => $this->dateTime()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
        
        $this->addForeignKey('fk-page-category_id', '{{%page}}', 'category_id', '{{%category}}', 'id', 'CASCADE');
        
        $this->createTable('{{%page_content}}', [
            'id'             => $this->primaryKey(),
            'page_id'        => $this->integer()->notNull(),
            'domain_id'      => $this->integer(),
            'language_id'    => $this->integer(),
            'name'           => $this->string(255)->notNull(),
            'h1'             => $this->string(255)->notNull(),
            'image'          => $this->string(255)->notNull(),
            'preview_text'   => $this->text()->notNull(),
            'full_text'      => $this->text()->notNull(),
            'title'          => $this->string(255)->notNull(),
            'og_title'       => $this->string(255)->notNull(),
            'keywords'       => $this->string(255)->notNull(),
            'description'    => $this->text()->notNull(),
            'og_description' => $this->text()->notNull(),
        ]);
        
        $this->addForeignKey('fk-page_content-page_id', '{{%page_content}}', 'page_id', '{{%page}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-page_content-domain_id', '{{%page_content}}', 'domain_id', '{{%domain}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-page_content-language_id', '{{%page_content}}', 'language_id', '{{%language}}', 'id', 'CASCADE');
    }
    
    private function createRelations()
    {
        $this->createTable('{{%category_assign}}', [
            'id'          => $this->primaryKey(),
            'resource_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'type'        => $this->integer(2)->notNull(),
            'source_type' => $this->integer(2)->notNull(),
        ]);
        
        $this->addForeignKey('fk-category_assign-category_id', '{{%category_assign}}', 'category_id', '{{%category}}', 'id', 'CASCADE');

        $this->createTable('{{%page_assign}}', [
            'id'          => $this->primaryKey(),
            'resource_id' => $this->integer()->notNull(),
            'page_id' => $this->integer()->notNull(),
            'type'        => $this->integer(2)->notNull(),
            'source_type' => $this->integer(2)->notNull(),
        ]);
        
        $this->addForeignKey('fk-page_assign-page_id', '{{%page_assign}}', 'page_id', '{{%page}}', 'id', 'CASCADE');
    }
    
    private function fillData()
    {
        $time = date('Y-m-d H:i:s');
        
        $this->insert('{{%category}}', [
            'url'         => '',
            'author_id'   => 0,
            'status'      => self::PUBLISH,
            'lft'         => 1,
            'rgt'         => 2,
            'depth'       => 0,
            'access_read' => self::ACCESS_READ,
            'publish_at'  => $time,
            'created_at'  => $time,
            'updated_at'  => $time
        ]);
    }
}
