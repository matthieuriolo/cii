<?php
namespace app\modules\cii\migrations;

use cii\db\Migration;

class Index extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* table Cii_Class*/
        $this->createTable('{{%Cii_Classname}}', [
            'id' => $this->primaryKey(),
            'path' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        /* table Cii_Content*/
        $this->createTable('{{%Cii_Content}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'enabled' => $this->integer()->notNull(),
            'created' => $this->dateTime()->notNull(),
            'classname_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Cii_Content_Cii_Class1',
            'Cii_Content',
            'classname_id',
            'Cii_Classname',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Cii_Content_Cii_Class1_idx',
            'Cii_Content',
            'classname_id'
        );

        /* table Cii_Language*/
        $this->createTable('{{%Cii_Language}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'enabled' => $this->integer()->notNull(),
            'code' => $this->string()->notNull()->unique(),
            'shortCode' => $this->string(),
        ], $tableOptions);

        /* table Cii_Route*/
        $this->createTable('{{%Cii_Route}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string()->notNull(),
            'language_id' => $this->integer(),
            'parent_id' => $this->integer(),
            'classname_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreSitemap_CoreLanguage1',
            'Cii_Route',
            'language_id',
            'Cii_Language',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_CoreSitemap_CoreSitemap1',
            'Cii_Route',
            'parent_id',
            'Cii_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_Route_Cii_Class1',
            'Cii_Route',
            'classname_id',
            'Cii_Classname',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            'fk_CoreSitemap_CoreLanguage1_idx',
            'Cii_Route',
            'language_id'
        );

        $this->createIndex(
            'fk_CoreSitemap_CoreSitemap1_idx',
            'Cii_Route',
            'parent_id'
        );

        $this->createIndex(
            'fk_Cii_Route_Cii_Class1_idx',
            'Cii_Route',
            'classname_id'
        );



        /* table Cii_Extension*/
        $this->createTable('{{%Cii_Extension}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'installed' => $this->dateTime()->notNull(),
            'enabled' => $this->integer()->notNull(),
            'classname_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_Extension_Core_Class1',
            'Cii_Extension',
            'classname_id',
            'Cii_Classname',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_Extension_Core_Class1_idx',
            'Cii_Extension',
            'classname_id'
        );

        /* table Cii_Configuration*/
        $this->createTable('{{%Cii_Configuration}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'value' => $this->string(),
            'extension_id' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_Configuration_Core_Extension1',
            'Cii_Configuration',
            'extension_id',
            'Cii_Extension',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'fk_Core_Configuration_Core_Extension1_idx',
            'Cii_Configuration',
            'extension_id'
        );

        /* table Cii_Layout*/
        $this->createTable('{{%Cii_Layout}}', [
            'id' => $this->primaryKey(),
            'extension_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_Layout_Core_Extension1',
            'Cii_Layout',
            'extension_id',
            'Cii_Extension',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_Layout_Core_Extension1_idx',
            'Cii_Layout',
            'extension_id'
        );

        /* table Cii_User*/
        $this->createTable('{{%Cii_User}}', [
            'id' => $this->primaryKey(),
            'created' => $this->dateTime()->notNull(),
            'activated' => $this->dateTime(),
            'username' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'password' => $this->string(),
            'enabled' => $this->integer()->notNull(),
            'language_id' => $this->integer(),
            'layout_id' => $this->integer(),
            'reset_token' => $this->string(),
            'activation_token' => $this->string(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreUser_CoreLanguage1',
            'Cii_User',
            'language_id',
            'Cii_Language',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Core_User_Core_Layout1',
            'Cii_User',
            'layout_id',
            'Cii_Layout',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->createIndex(
            'fk_CoreUser_CoreLanguage1_idx',
            'Cii_User',
            'language_id'
        );

        $this->createIndex(
            'fk_Core_User_Core_Layout1_idx',
            'Cii_User',
            'layout_id'
        );

        /* table Cii_Group*/
        $this->createTable('{{%Cii_Group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        /* table Cii_GroupMembers*/
        $this->createTable('{{%Cii_GroupMembers}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreUserGroup_CoreUser1',
            'Cii_GroupMembers',
            'user_id',
            'Cii_User',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_CoreUserGroup_CoreGroup1',
            'Cii_GroupMembers',
            'group_id',
            'Cii_Group',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_CoreUserGroup_CoreUser1_idx',
            'Cii_GroupMembers',
            'user_id'
        );

        $this->createIndex(
            'fk_CoreUserGroup_CoreGroup1_idx',
            'Cii_GroupMembers',
            'group_id'
        );

        /* table Cii_Package*/
        $this->createTable('{{%Cii_Package}}', [
            'id' => $this->primaryKey(),
            'extension_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_Module_Core_Extension1',
            'Cii_Package',
            'extension_id',
            'Cii_Extension',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_Module_Core_Extension1_idx',
            'Cii_Package',
            'extension_id'
        );

        /* table Cii_Permission*/
        $this->createTable('{{%Cii_Permission}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'enabled' => $this->integer()->notNull(),
            'group_id' => $this->integer()->notNull(),
            'module_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Permission_CoreGroup1',
            'Cii_Permission',
            'group_id',
            'Cii_Group',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_CorePermission_CoreModule1',
            'Cii_Permission',
            'module_id',
            'Cii_Package',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Permission_CoreGroup1_idx',
            'Cii_Permission',
            'group_id'
        );

        $this->createIndex(
            'fk_CorePermission_CoreModule1_idx',
            'Cii_Permission',
            'module_id'
        );

        /* table Cii_AuthContent*/
        $this->createTable('{{%Cii_AuthContent}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'content_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreAuthView_CoreView1',
            'Cii_AuthContent',
            'content_id',
            'Cii_Content',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_CoreAuthView_CoreView1_idx',
            'Cii_AuthContent',
            'content_id'
        );

        /* table Cii_ContentVisibilities*/
        $this->createTable('{{%Cii_ContentVisibilities}}', [
            'id' => $this->primaryKey(),
            'content_id' => $this->integer()->notNull(),
            'route_id' => $this->integer(),
            'language_id' => $this->integer(),
            'ordering' => $this->integer()->notNull(),
            'position' => $this->string(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreViewVisibility_CoreView1',
            'Cii_ContentVisibilities',
            'content_id',
            'Cii_Content',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_CoreViewVisibility_CoreSitemap1',
            'Cii_ContentVisibilities',
            'route_id',
            'Cii_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_CoreViewVisibility_CoreLanguage1',
            'Cii_ContentVisibilities',
            'language_id',
            'Cii_Language',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_CoreViewVisibility_CoreView1_idx',
            'Cii_ContentVisibilities',
            'content_id'
        );

        $this->createIndex(
            'fk_CoreViewVisibility_CoreSitemap1_idx',
            'Cii_ContentVisibilities',
            'route_id'
        );

        $this->createIndex(
            'fk_CoreViewVisibility_CoreLanguage1_idx',
            'Cii_ContentVisibilities',
            'language_id'
        );

        /* table Cii_BackendRoute*/
        $this->createTable('{{%Cii_BackendRoute}}', [
            'id' => $this->primaryKey(),
            'route_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_BackendRoute_Core_Route1',
            'Cii_BackendRoute',
            'route_id',
            'Cii_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_BackendRoute_Core_Route1_idx',
            'Cii_BackendRoute',
            'route_id'
        );

        /* table Cii_ContentRoute*/
        $this->createTable('{{%Cii_ContentRoute}}', [
            'id' => $this->primaryKey(),
            'route_id' => $this->integer()->notNull(),
            'content_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_PositionRoute_Core_Route1',
            'Cii_ContentRoute',
            'route_id',
            'Cii_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Core_ContentRoute_Core_Content1',
            'Cii_ContentRoute',
            'content_id',
            'Cii_Content',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_PositionRoute_Core_Route1_idx',
            'Cii_ContentRoute',
            'route_id'
        );

        $this->createIndex(
            'fk_Core_ContentRoute_Core_Content1_idx',
            'Cii_ContentRoute',
            'content_id'
        );

        /* table Cii_GiiRoute*/
        $this->createTable('{{%Cii_GiiRoute}}', [
            'id' => $this->primaryKey(),
            'route_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_GiiRoute_Core_Route1',
            'Cii_GiiRoute',
            'route_id',
            'Cii_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_GiiRoute_Core_Route1_idx',
            'Cii_GiiRoute',
            'route_id'
        );

        /* table Cii_DocRoute*/
        $this->createTable('{{%Cii_DocRoute}}', [
            'id' => $this->primaryKey(),
            'route_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_DocRoute_Core_Route1',
            'Cii_DocRoute',
            'route_id',
            'Cii_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_DocRoute_Core_Route1_idx',
            'Cii_DocRoute',
            'route_id'
        );

        /* table Cii_LanguageMessages*/
        $this->createTable('{{%Cii_LanguageMessages}}', [
            'id' => $this->primaryKey(),
            'language_id' => $this->integer()->notNull(),
            'extension_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_LanguageMessages_Core_Language1',
            'Cii_LanguageMessages',
            'language_id',
            'Cii_Language',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Core_LanguageMessages_Core_Extension1',
            'Cii_LanguageMessages',
            'extension_id',
            'Cii_Extension',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_LanguageMessages_Core_Language1_idx',
            'Cii_LanguageMessages',
            'language_id'
        );

        $this->createIndex(
            'fk_Core_LanguageMessages_Core_Extension1_idx',
            'Cii_LanguageMessages',
            'extension_id'
        );



        //add default data
        /*
        $this->insert('Core_Language', [
            'name' => 'English',
            'code' => 'en-GB',
            'shortCode' => 'en',
        ]);

        $this->insert('Core_Route', [
            'slug' => 'admin',
            'language_id' => ,
        ]);

        $this->insert('Core_BackendRoute', [
            'route_id' => ,
        ]);*/
    }

    public function down() {
        $this->dropTable('{{%Core_AuthContent}}');
        $this->dropTable('{{%Core_BackendRoute}}');
        $this->dropTable('{{%Core_ContentVisibilities}}');
        $this->dropTable('{{%Core_GroupMembers}}');
        $this->dropTable('{{%Core_ContentRoute}}');
        $this->dropTable('{{%Core_Content}}');
        $this->dropTable('{{%Core_Configuration}}');
        $this->dropTable('{{%Core_Permission}}');
        $this->dropTable('{{%Core_Module}}');
        $this->dropTable('{{%Core_Route}}');
        $this->dropTable('{{%Core_User}}');
        $this->dropTable('{{%Core_Language}}');
        $this->dropTable('{{%Core_Group}}');
        $this->dropTable('{{%Core_Layout}}');
    }
}
