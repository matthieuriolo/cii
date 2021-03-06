<?php
namespace app\modules\cii\migrations;

use cii\db\Migration;

class Index extends Migration {
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* table Cii_Abstract_Extension */
        $this->createTable('{{%Cii_Abstract_Extension}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'installed' => $this->dateTime()->notNull(),
            'enabled' => $this->boolean()->notNull(),
            'classname_id' => $this->integer()->notNull()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_Extension_Core_Class1',
            'Cii_Abstract_Extension',
            'classname_id',
            'Cii_Classname',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_Extension_Core_Class1_idx',
            'Cii_Abstract_Extension',
            'classname_id'
        );

        $this->createIndex(
            'installed_idx',
            'Cii_Abstract_Extension',
            'installed'
        );

        $this->createIndex(
            'enabled_idx',
            'Cii_Abstract_Extension',
            'enabled'
        );

        $this->createIndex(
            'name_fulltext',
            'Cii_Abstract_Extension',
            'name'
        );

        /* table Cii_Package */
        $this->createTable('{{%Cii_Package}}', [
            'id' => $this->primaryKey()->unsigned(),
            'extension_id' => $this->integer()->notNull()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_Module_Core_Extension1',
            'Cii_Package',
            'extension_id',
            'Cii_Abstract_Extension',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_Module_Core_Extension1_idx',
            'Cii_Package',
            'extension_id'
        );

        /* table Cii_Classname */
        $this->createTable('{{%Cii_Classname}}', [
            'id' => $this->primaryKey()->unsigned(),
            'path' => $this->string(255)->notNull()->unique(),
            'package_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Cii_Classname_Cii_Package1',
            'Cii_Classname',
            'package_id',
            'Cii_Package',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'fk_Cii_Classname_Cii_Package1_idx',
            'Cii_Classname',
            'package_id'
        );

        /* table Cii_Mandate */
        $this->createTable('{{%Cii_Mandate}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'enabled' => $this->boolean()->notNull(),
            'created' => $this->dateTime()->notNull(),
        ], $tableOptions);

        /* table Cii_Abstract_Content */
        $this->createTable('{{%Cii_Abstract_Content}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull()->unique(),
            'description' => $this->string(255),
            'enabled' => $this->boolean()->notNull(),
            'show_name' => $this->boolean()->notNull(),
            'columns_count' => $this->integer()->unsigned(),
            'created' => $this->dateTime()->notNull(),
            'classname_id' => $this->integer()->notNull()->unsigned(),
            'mandate_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Cii_Content_Cii_Class1',
            'Cii_Abstract_Content',
            'classname_id',
            'Cii_Classname',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_Abstract_Content_Cii_Mandate1',
            'Cii_Abstract_Content',
            'mandate_id',
            'Cii_Mandate',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'fk_Cii_Content_Cii_Class1_idx',
            'Cii_Abstract_Content',
            'classname_id'
        );

        $this->createIndex(
            'enabled_idx',
            'Cii_Abstract_Content',
            'enabled'
        );

        $this->createIndex(
            'created_idx',
            'Cii_Abstract_Content',
            'created'
        );

        $this->createIndex(
            'name_fulltext',
            'Cii_Abstract_Content',
            'name'
        );

        $this->createIndex(
            'fk_Cii_Abstract_Content_Cii_Mandate1_idx',
            'Cii_Abstract_Content',
            'mandate_id'
        );

        /* table Cii_Language */
        $this->createTable('{{%Cii_Language}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(45)->notNull(),
            'enabled' => $this->boolean()->notNull(),
            'created' => $this->dateTime()->notNull(),
            'code' => $this->string(6)->notNull()->unique(),
            'shortcode' => $this->string(2),
            'date' => $this->string(12),
            'time' => $this->string(8),
            'datetime' => $this->string(12),
            'decimalSeparator' => $this->string(8),
            'thousandSeparator' => $this->string(8),
            'decimals' => $this->integer(1),
            'removeZeros' => $this->boolean(),
            'currencySymbol' => $this->string(8),
            'currencySymbolPlace' => $this->boolean(),
            'currencySmallestUnit' => $this->float(),
            'currencyRemoveZeros' => $this->boolean(),
        ], $tableOptions);

        $this->createIndex(
            'enabled_idx',
            'Cii_Language',
            'enabled'
        );

        $this->createIndex(
            'name_fulltext',
            'Cii_Language',
            'name'
        );

        /* table Cii_Abstract_Route */
        $this->createTable('{{%Cii_Abstract_Route}}', [
            'id' => $this->primaryKey()->unsigned(),
            'slug' => $this->string(255)->notNull()->unique(),
            'title' => $this->string(255),
            'enabled' => $this->boolean()->notNull(),
            'created' => $this->dateTime()->notNull(),
            'language_id' => $this->integer()->unsigned(),
            'parent_id' => $this->integer()->unsigned(),
            'classname_id' => $this->integer()->notNull()->unsigned(),
            'hits' => $this->integer()->notNull(),
            'mandate_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreSitemap_CoreLanguage1',
            'Cii_Abstract_Route',
            'language_id',
            'Cii_Language',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_CoreSitemap_CoreSitemap1',
            'Cii_Abstract_Route',
            'parent_id',
            'Cii_Abstract_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_Route_Cii_Class1',
            'Cii_Abstract_Route',
            'classname_id',
            'Cii_Classname',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_Abstract_Route_Cii_Mandate1',
            'Cii_Abstract_Route',
            'mandate_id',
            'Cii_Mandate',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'fk_CoreSitemap_CoreLanguage1_idx',
            'Cii_Abstract_Route',
            'language_id'
        );

        $this->createIndex(
            'fk_CoreSitemap_CoreSitemap1_idx',
            'Cii_Abstract_Route',
            'parent_id'
        );

        $this->createIndex(
            'fk_Cii_Route_Cii_Class1_idx',
            'Cii_Abstract_Route',
            'classname_id'
        );

        $this->createIndex(
            'slug_idx',
            'Cii_Abstract_Route',
            'slug'
        );

        $this->createIndex(
            'created_idx',
            'Cii_Abstract_Route',
            'created'
        );

        $this->createIndex(
            'enabled_idx',
            'Cii_Abstract_Route',
            'enabled'
        );

        $this->createIndex(
            'title_idx',
            'Cii_Abstract_Route',
            'title'
        );

        $this->createIndex(
            'hits_idx',
            'Cii_Abstract_Route',
            'hits'
        );

        $this->createIndex(
            'slug_fulltext',
            'Cii_Abstract_Route',
            'slug'
        );

        $this->createIndex(
            'fk_Cii_Abstract_Route_Cii_Mandate1_idx',
            'Cii_Abstract_Route',
            'mandate_id'
        );

        /* table Cii_Configuration */
        $this->createTable('{{%Cii_Configuration}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(45)->notNull()->unique(),
            'value' => $this->string(45),
            'extension_id' => $this->integer()->unsigned(),
            'mandate_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_Configuration_Core_Extension1',
            'Cii_Configuration',
            'extension_id',
            'Cii_Abstract_Extension',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_Cii_Configuration_Cii_Mandate1',
            'Cii_Configuration',
            'mandate_id',
            'Cii_Mandate',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_Configuration_Core_Extension1_idx',
            'Cii_Configuration',
            'extension_id'
        );

        $this->createIndex(
            'fk_Cii_Configuration_Cii_Mandate1_idx',
            'Cii_Configuration',
            'mandate_id'
        );

        /* table Cii_Layout */
        $this->createTable('{{%Cii_Layout}}', [
            'id' => $this->primaryKey()->unsigned(),
            'extension_id' => $this->integer()->notNull()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_Layout_Core_Extension1',
            'Cii_Layout',
            'extension_id',
            'Cii_Abstract_Extension',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_Layout_Core_Extension1_idx',
            'Cii_Layout',
            'extension_id'
        );

        /* table Cii_User */
        $this->createTable('{{%Cii_User}}', [
            'id' => $this->primaryKey()->unsigned(),
            'created' => $this->dateTime()->notNull(),
            'activated' => $this->dateTime(),
            'username' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull()->unique(),
            'password' => $this->string(64),
            'superadmin' => $this->boolean()->notNull(),
            'enabled' => $this->boolean()->notNull(),
            'timezone' => $this->string(45),
            'language_id' => $this->integer()->unsigned(),
            'layout_id' => $this->integer()->unsigned(),
            'token' => $this->string(64),
            'mandate_id' => $this->integer()->unsigned(),
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

        $this->addForeignKey(
            'fk_Cii_User_Cii_Mandate1',
            'Cii_User',
            'mandate_id',
            'Cii_Mandate',
            'id',
            'CASCADE',
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

        $this->createIndex(
            'enabled_idx',
            'Cii_User',
            'enabled'
        );

        $this->createIndex(
            'created_idx',
            'Cii_User',
            'created'
        );

        $this->createIndex(
            'activated_idx',
            'Cii_User',
            'activated'
        );

        $this->createIndex(
            'username__fulltext',
            'Cii_User',
            'username'
        );

        $this->createIndex(
            'superadmin_idx',
            'Cii_User',
            'superadmin'
        );

        $this->createIndex(
            'email_fulltext',
            'Cii_User',
            'email'
        );

        $this->createIndex(
            'fk_Cii_User_Cii_Mandate1_idx',
            'Cii_User',
            'mandate_id'
        );

        /* table Cii_Group */
        $this->createTable('{{%Cii_Group}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull()->unique(),
            'enabled' => $this->boolean()->notNull(),
            'created' => $this->dateTime()->notNull(),
            'mandate_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Cii_Group_Cii_Mandate1',
            'Cii_Group',
            'mandate_id',
            'Cii_Mandate',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'created_idx',
            'Cii_Group',
            'created'
        );

        $this->createIndex(
            'enabled_idx',
            'Cii_Group',
            'enabled'
        );

        $this->createIndex(
            'name_fulltext',
            'Cii_Group',
            'name'
        );

        $this->createIndex(
            'fk_Cii_Group_Cii_Mandate1_idx',
            'Cii_Group',
            'mandate_id'
        );

        /* table Cii_GroupMembers */
        $this->createTable('{{%Cii_GroupMembers}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->unsigned(),
            'group_id' => $this->integer()->notNull()->unsigned(),
            'created' => $this->dateTime()->notNull(),
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

        $this->createIndex(
            'created_idx',
            'Cii_GroupMembers',
            'created'
        );

        /* table Cii_Permission */
        $this->createTable('{{%Cii_Permission}}', [
            'id' => $this->primaryKey()->unsigned(),
            'permission_id' => $this->integer()->notNull()->unsigned()->unique(),
            'group_id' => $this->integer()->notNull()->unsigned(),
            'package_id' => $this->integer()->notNull()->unsigned(),
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
            'package_id',
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
            'package_id'
        );

        $this->createIndex(
            'fk_permission',
            'Cii_Permission',
            'permission_id'
        );

        /* table Cii_UserLoginContent */
        $this->createTable('{{%Cii_UserLoginContent}}', [
            'id' => $this->primaryKey()->unsigned(),
            'content_id' => $this->integer()->notNull()->unsigned(),
            'redirect_id' => $this->integer()->unsigned(),
            'register_id' => $this->integer()->unsigned(),
            'forgot_id' => $this->integer()->unsigned(),
            'captcha_id' => $this->integer()->unsigned(),
            'remember_visible' => $this->boolean(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreAuthView_CoreView1',
            'Cii_UserLoginContent',
            'content_id',
            'Cii_Abstract_Content',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route1',
            'Cii_UserLoginContent',
            'redirect_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route2',
            'Cii_UserLoginContent',
            'register_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route3',
            'Cii_UserLoginContent',
            'forgot_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_UserLoginContent_Cii_Route1',
            'Cii_UserLoginContent',
            'captcha_id',
            'Cii_Abstract_Route',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'fk_CoreAuthView_CoreView1_idx',
            'Cii_UserLoginContent',
            'content_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route1_idx',
            'Cii_UserLoginContent',
            'redirect_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route2_idx',
            'Cii_UserLoginContent',
            'register_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route3_idx',
            'Cii_UserLoginContent',
            'forgot_id'
        );

        $this->createIndex(
            'fk_Cii_UserLoginContent_Cii_Route1_idx',
            'Cii_UserLoginContent',
            'captcha_id'
        );

        /* table Cii_ContentVisibilities */
        $this->createTable('{{%Cii_ContentVisibilities}}', [
            'id' => $this->primaryKey()->unsigned(),
            'content_id' => $this->integer()->notNull()->unsigned(),
            'route_id' => $this->integer()->unsigned(),
            'show_layout' => $this->boolean()->notNull(),
            'language_id' => $this->integer()->unsigned(),
            'ordering' => $this->integer()->notNull()->unsigned(),
            'position' => $this->string(255),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreViewVisibility_CoreView1',
            'Cii_ContentVisibilities',
            'content_id',
            'Cii_Abstract_Content',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_CoreViewVisibility_CoreSitemap1',
            'Cii_ContentVisibilities',
            'route_id',
            'Cii_Abstract_Route',
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

        $this->createIndex(
            'ordering_idx',
            'Cii_ContentVisibilities',
            'ordering'
        );

        $this->createIndex(
            'position_idx',
            'Cii_ContentVisibilities',
            'position'
        );

        /* table Cii_BackendRoute */
        $this->createTable('{{%Cii_BackendRoute}}', [
            'id' => $this->primaryKey()->unsigned(),
            'route_id' => $this->integer()->notNull()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_BackendRoute_Core_Route1',
            'Cii_BackendRoute',
            'route_id',
            'Cii_Abstract_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_BackendRoute_Core_Route1_idx',
            'Cii_BackendRoute',
            'route_id'
        );

        /* table Cii_ContentRoute */
        $this->createTable('{{%Cii_ContentRoute}}', [
            'id' => $this->primaryKey()->unsigned(),
            'route_id' => $this->integer()->notNull()->unsigned(),
            'content_id' => $this->integer()->notNull()->unsigned(),
            'keys' => $this->string(255),
            'description' => $this->string(255),
            'robots' => $this->string(16),
            'type' => $this->string(24),
            'image' => $this->string(255),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_PositionRoute_Core_Route1',
            'Cii_ContentRoute',
            'route_id',
            'Cii_Abstract_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Core_ContentRoute_Core_Content1',
            'Cii_ContentRoute',
            'content_id',
            'Cii_Abstract_Content',
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

        /* table Cii_GiiRoute */
        $this->createTable('{{%Cii_GiiRoute}}', [
            'id' => $this->primaryKey()->unsigned(),
            'route_id' => $this->integer()->notNull()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_GiiRoute_Core_Route1',
            'Cii_GiiRoute',
            'route_id',
            'Cii_Abstract_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_GiiRoute_Core_Route1_idx',
            'Cii_GiiRoute',
            'route_id'
        );

        /* table Cii_DocRoute */
        $this->createTable('{{%Cii_DocRoute}}', [
            'id' => $this->primaryKey()->unsigned(),
            'route_id' => $this->integer()->notNull()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Core_DocRoute_Core_Route1',
            'Cii_DocRoute',
            'route_id',
            'Cii_Abstract_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Core_DocRoute_Core_Route1_idx',
            'Cii_DocRoute',
            'route_id'
        );

        /* table Cii_LanguageMessages */
        $this->createTable('{{%Cii_LanguageMessages}}', [
            'id' => $this->primaryKey(),
            'language_id' => $this->integer()->notNull()->unsigned(),
            'extension_id' => $this->integer()->notNull()->unsigned(),
            'translatedExtension_id' => $this->integer()->notNull()->unsigned(),
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
            'Cii_Abstract_Extension',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_LanguageMessages_Cii_Extension1',
            'Cii_LanguageMessages',
            'translatedExtension_id',
            'Cii_Abstract_Extension',
            'id',
            'NO ACTION',
            'NO ACTION'
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

        $this->createIndex(
            'fk_Cii_LanguageMessages_Cii_Extension1_idx',
            'Cii_LanguageMessages',
            'translatedExtension_id'
        );

        /* table Cii_UserLogoutContent */
        $this->createTable('{{%Cii_UserLogoutContent}}', [
            'id' => $this->primaryKey()->unsigned(),
            'content_id' => $this->integer()->notNull()->unsigned(),
            'redirect_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreAuthView_CoreView10',
            'Cii_UserLogoutContent',
            'content_id',
            'Cii_Abstract_Content',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route10',
            'Cii_UserLogoutContent',
            'redirect_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->createIndex(
            'fk_CoreAuthView_CoreView1_idx',
            'Cii_UserLogoutContent',
            'content_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route1_idx',
            'Cii_UserLogoutContent',
            'redirect_id'
        );

        /* table Cii_UserRegisterContent */
        $this->createTable('{{%Cii_UserRegisterContent}}', [
            'id' => $this->primaryKey()->unsigned(),
            'content_id' => $this->integer()->notNull()->unsigned(),
            'activate_id' => $this->integer()->notNull()->unsigned(),
            'redirect_id' => $this->integer()->unsigned(),
            'login_id' => $this->integer()->unsigned(),
            'forgot_id' => $this->integer()->unsigned(),
            'captcha_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreAuthView_CoreView11',
            'Cii_UserRegisterContent',
            'content_id',
            'Cii_Abstract_Content',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route11',
            'Cii_UserRegisterContent',
            'redirect_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route20',
            'Cii_UserRegisterContent',
            'login_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route30',
            'Cii_UserRegisterContent',
            'forgot_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_UserRegisterContent_Cii_Route1',
            'Cii_UserRegisterContent',
            'activate_id',
            'Cii_Abstract_Route',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_UserRegisterContent_Cii_Route2',
            'Cii_UserRegisterContent',
            'captcha_id',
            'Cii_Abstract_Route',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'fk_CoreAuthView_CoreView1_idx',
            'Cii_UserRegisterContent',
            'content_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route1_idx',
            'Cii_UserRegisterContent',
            'redirect_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route2_idx',
            'Cii_UserRegisterContent',
            'login_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route3_idx',
            'Cii_UserRegisterContent',
            'forgot_id'
        );

        $this->createIndex(
            'fk_Cii_UserRegisterContent_Cii_Route1_idx',
            'Cii_UserRegisterContent',
            'activate_id'
        );

        $this->createIndex(
            'fk_Cii_UserRegisterContent_Cii_Route2_idx',
            'Cii_UserRegisterContent',
            'captcha_id'
        );

        /* table Cii_UserActivateContent */
        $this->createTable('{{%Cii_UserActivateContent}}', [
            'id' => $this->primaryKey()->unsigned(),
            'content_id' => $this->integer()->notNull()->unsigned(),
            'redirect_id' => $this->integer()->unsigned(),
            'login_id' => $this->integer()->unsigned(),
            'register_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreAuthView_CoreView110',
            'Cii_UserActivateContent',
            'content_id',
            'Cii_Abstract_Content',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route110',
            'Cii_UserActivateContent',
            'redirect_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route200',
            'Cii_UserActivateContent',
            'login_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_UserRegisterContent_Cii_Route10',
            'Cii_UserActivateContent',
            'register_id',
            'Cii_Abstract_Route',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'fk_CoreAuthView_CoreView1_idx',
            'Cii_UserActivateContent',
            'content_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route1_idx',
            'Cii_UserActivateContent',
            'redirect_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route2_idx',
            'Cii_UserActivateContent',
            'login_id'
        );

        $this->createIndex(
            'fk_Cii_UserRegisterContent_Cii_Route1_idx',
            'Cii_UserActivateContent',
            'register_id'
        );

        /* table Cii_UserForgotContent */
        $this->createTable('{{%Cii_UserForgotContent}}', [
            'id' => $this->primaryKey()->unsigned(),
            'content_id' => $this->integer()->notNull()->unsigned(),
            'redirect_id' => $this->integer()->unsigned(),
            'login_id' => $this->integer()->unsigned(),
            'register_id' => $this->integer()->unsigned(),
            'captcha_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_CoreAuthView_CoreView12',
            'Cii_UserForgotContent',
            'content_id',
            'Cii_Abstract_Content',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route12',
            'Cii_UserForgotContent',
            'redirect_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route21',
            'Cii_UserForgotContent',
            'register_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_AuthContent_Cii_Route31',
            'Cii_UserForgotContent',
            'login_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_UserForgotContent_Cii_Route1',
            'Cii_UserForgotContent',
            'captcha_id',
            'Cii_Abstract_Route',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'fk_CoreAuthView_CoreView1_idx',
            'Cii_UserForgotContent',
            'content_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route1_idx',
            'Cii_UserForgotContent',
            'redirect_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route2_idx',
            'Cii_UserForgotContent',
            'register_id'
        );

        $this->createIndex(
            'fk_Cii_AuthContent_Cii_Route3_idx',
            'Cii_UserForgotContent',
            'login_id'
        );

        $this->createIndex(
            'fk_Cii_UserForgotContent_Cii_Route1_idx',
            'Cii_UserForgotContent',
            'captcha_id'
        );

        /* table Cii_MailTemplate */
        $this->createTable('{{%Cii_MailTemplate}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'package_id' => $this->integer()->notNull()->unsigned(),
            'subject' => $this->string(255)->notNull(),
            'content_text' => $this->text()->notNull(),
            'content_html' => $this->text()->notNull(),
            'language_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Cii_MailTemplate_Cii_Package1',
            'Cii_MailTemplate',
            'package_id',
            'Cii_Package',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_Cii_MailTemplate_Cii_Language1',
            'Cii_MailTemplate',
            'language_id',
            'Cii_Language',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'fk_Cii_MailTemplate_Cii_Package1_idx',
            'Cii_MailTemplate',
            'package_id'
        );

        $this->createIndex(
            'fk_Cii_MailTemplate_Cii_Language1_idx',
            'Cii_MailTemplate',
            'language_id'
        );

        /* table Cii_ProfileRoute */
        $this->createTable('{{%Cii_ProfileRoute}}', [
            'id' => $this->primaryKey()->unsigned(),
            'route_id' => $this->integer()->notNull()->unsigned(),
            'show_groups' => $this->boolean()->notNull(),
            'can_change_layout' => $this->boolean()->notNull(),
            'can_change_language' => $this->boolean()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Cii_UserProfileRoute_Cii_Route1',
            'Cii_ProfileRoute',
            'route_id',
            'Cii_Abstract_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Cii_UserProfileRoute_Cii_Route1_idx',
            'Cii_ProfileRoute',
            'route_id'
        );

        /* table Cii_CountAccess */
        $this->createTable('{{%Cii_CountAccess}}', [
            'id' => $this->primaryKey()->unsigned(),
            'hits' => $this->integer()->notNull()->unsigned(),
            'created' => $this->date()->notNull(),
            'route_id' => $this->integer()->notNull()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Cii_CountAccess_Cii_Route1',
            'Cii_CountAccess',
            'route_id',
            'Cii_Abstract_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'created_idx',
            'Cii_CountAccess',
            'created'
        );

        $this->createIndex(
            'fk_Cii_CountAccess_Cii_Route1_idx',
            'Cii_CountAccess',
            'route_id'
        );

        /* table Cii_RedirectRoute */
        $this->createTable('{{%Cii_RedirectRoute}}', [
            'id' => $this->primaryKey()->unsigned(),
            'route_id' => $this->integer()->notNull()->unsigned(),
            'redirect_id' => $this->integer()->unsigned(),
            'type' => $this->integer()->notNull()->unsigned(),
            'url' => $this->string(255),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Cii_Redirect_Cii_Route1',
            'Cii_RedirectRoute',
            'route_id',
            'Cii_Abstract_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_Cii_Redirect_Cii_Route2',
            'Cii_RedirectRoute',
            'redirect_id',
            'Cii_Abstract_Route',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Cii_Redirect_Cii_Route1_idx',
            'Cii_RedirectRoute',
            'route_id'
        );

        $this->createIndex(
            'fk_Cii_Redirect_Cii_Route2_idx',
            'Cii_RedirectRoute',
            'redirect_id'
        );

        /* table Cii_CaptchaRoute */
        $this->createTable('{{%Cii_CaptchaRoute}}', [
            'id' => $this->primaryKey()->unsigned(),
            'route_id' => $this->integer()->notNull()->unsigned(),
            'length_min' => $this->integer(),
            'length_max' => $this->integer(),
            'font_color' => $this->string(26),
            'limit' => $this->integer(),
            'width' => $this->integer(),
            'height' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Cii_Captcha_Cii_Route1',
            'Cii_CaptchaRoute',
            'route_id',
            'Cii_Abstract_Route',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_Cii_Captcha_Cii_Route1_idx',
            'Cii_CaptchaRoute',
            'route_id'
        );

        /* table Cii_MandateMembers */
        $this->createTable('{{%Cii_MandateMembers}}', [
            'id' => $this->integer()->unsigned(),
            'mandate_id' => $this->integer()->notNull()->unsigned(),
            'user_id' => $this->integer()->notNull()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_Cii_MandateMembers_Cii_Mandate1',
            'Cii_MandateMembers',
            'mandate_id',
            'Cii_Mandate',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_Cii_MandateMembers_Cii_User1',
            'Cii_MandateMembers',
            'user_id',
            'Cii_User',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'fk_Cii_MandateMembers_Cii_Mandate1_idx',
            'Cii_MandateMembers',
            'mandate_id'
        );

        $this->createIndex(
            'fk_Cii_MandateMembers_Cii_User1_idx',
            'Cii_MandateMembers',
            'user_id'
        );



        //add default data
        /*
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
