<?php

use yii\db\Migration;

/**
 * Class m191128_054256_create_blog_tables
 */
class m191128_054256_blog extends Migration
{
    const PUBLISH     = 2;
    const ACCESS_READ = 'everyone';
    
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
        $this->dropTable('{{%page_assign}}');
        $this->dropTable('{{%category_assign}}');
        
        $this->dropTable('{{%page_content}}');
        $this->dropTable('{{%category_content}}');
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
            'access_read'       => $this->string(255)->notNull(),
            'records_per_page'  => $this->integer()->notNull()->defaultValue(15),
            'sort'              => $this->string(255),
            'main_template'     => $this->string(255),
            'category_template' => $this->string(255),
            'page_template'     => $this->string(255),
            'settings'          => $this->text(),
            'publish_at'        => $this->dateTime()->notNull(),
            'created_at'        => $this->dateTime()->notNull(),
            'updated_at'        => $this->dateTime()->notNull(),
        ]);
        
        $this->createTable('{{%category_content}}', [
            'id'             => $this->primaryKey(),
            'src_id'    => $this->integer()->notNull(),
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
            'og_url'         => $this->string(255)->notNull(),
            'og_sitename'    => $this->string(255)->notNull(),
            'og_type'        => $this->string(255)->notNull(),
            'tags'           => $this->string(255)
        ]);
        
        $this->addForeignKey('fk-content_category-src_id', '{{%category_content}}', 'src_id', '{{%category}}', 'id', 'CASCADE');
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
            'access_read' => $this->string(255)->notNull(),
            'main_template' => $this->string(255),
            'page_template' => $this->string(255),
            'settings'      => $this->text(),
            'publish_at' => $this->dateTime()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
        
        $this->addForeignKey('fk-page-category_id', '{{%page}}', 'category_id', '{{%category}}', 'id', 'CASCADE');
        
        $this->createTable('{{%page_content}}', [
            'id'             => $this->primaryKey(),
            'src_id'         => $this->integer()->notNull(),
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
            'og_url'         => $this->string(255),
            'og_sitename'    => $this->string(255),
            'og_type'        => $this->string(255),
            'tags'           => $this->string(255),
        ]);
        
        $this->addForeignKey('fk-page_content-src_id', '{{%page_content}}', 'src_id', '{{%page}}', 'id', 'CASCADE');
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
            'rgt'         => 4,
            'depth'       => 0,
            'access_read' => self::ACCESS_READ,
            'publish_at'  => $time,
            'created_at'  => $time,
            'updated_at'  => $time
        ]);
        
        $rootId = $this->db->lastInsertID;
        
        $this->insert('{{%category}}', [
            'url'         => 'first',
            'parent_id'   => $rootId,
            'author_id'   => 0,
            'status'      => self::PUBLISH,
            'lft'         => 2,
            'rgt'         => 3,
            'depth'       => 1,
            'access_read' => self::ACCESS_READ,
            'publish_at'  => $time,
            'created_at'  => $time,
            'updated_at'  => $time
        ]);
        
        $categoryId = $this->db->lastInsertID;
        
        $this->insert('{{%category_content}}', [
            'src_id'         => $categoryId,
            'domain_id'      => null,
            'language_id'    => null,
            'name'           => 'Home Category',
            'h1'             => 'Home Category',
            'image'          => '',
            'preview_text'   => 'Home Category',
            'full_text'      => 'Home Category',
            'title'          => 'Home Category',
            'og_title'       => 'Home Category',
            'keywords'       => 'Home Category',
            'description'    => 'Home Category',
            'og_description' => 'Home Category',
            'og_url'         => '',
            'og_sitename'    => '',
            'og_type'        => '',
        ]);
        
        
        
        $this->insert('{{%page}}', [            
            'url'         => 'home',
            'author_id'   => 0,
            'category_id' => $rootId,
            'status'      => self::PUBLISH,
            'access_read' => self::ACCESS_READ,
            'publish_at'  => $time,
            'created_at'  => $time,
            'updated_at'  => $time
        ]);
        
        $pageId = $this->db->lastInsertID;
        
        $this->insert('{{%page_content}}', [
            'src_id'         => $pageId,
            'domain_id'      => null,
            'language_id'    => null,
            'name'           => 'Home Page',
            'h1'             => 'Home Page',
            'image'          => '',
            'preview_text'   => 'Home Page',
            'full_text'      => 'Home Page',
            'title'          => 'Home Page',
            'og_title'       => 'Home Page',
            'keywords'       => 'Home Page',
            'description'    => 'Home Page',
            'og_description' => 'Home Page',
            'og_url'         => '',
            'og_sitename'    => '',
            'og_type'        => ''
        ]);
    }
}
