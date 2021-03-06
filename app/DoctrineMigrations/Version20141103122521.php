<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141103122521 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE MessageMetadata (id INT AUTO_INCREMENT NOT NULL, message_id INT DEFAULT NULL, participant_id INT DEFAULT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_DA67B3AD537A1329 (message_id), INDEX IDX_DA67B3AD9D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE Message (id INT AUTO_INCREMENT NOT NULL, thread_id INT DEFAULT NULL, sender_id INT DEFAULT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_790009E3E2904019 (thread_id), INDEX IDX_790009E3F624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ThreadMetadata (id INT AUTO_INCREMENT NOT NULL, thread_id INT DEFAULT NULL, participant_id INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, last_participant_message_date DATETIME DEFAULT NULL, last_message_date DATETIME DEFAULT NULL, INDEX IDX_BEF427AEE2904019 (thread_id), INDEX IDX_BEF427AE9D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE Thread (id INT AUTO_INCREMENT NOT NULL, subject VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, isSpam TINYINT(1) NOT NULL, createdBy_id INT DEFAULT NULL, INDEX IDX_368C49B53174800F (createdBy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE tips (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, title LONGTEXT NOT NULL, content LONGTEXT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_642C4108A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE tip_category (tip_id VARCHAR(255) NOT NULL, category_id VARCHAR(255) NOT NULL, INDEX IDX_4FCC19CE476C47F6 (tip_id), INDEX IDX_4FCC19CE12469DE2 (category_id), PRIMARY KEY(tip_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE user_infos (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, relationship_user_id INT DEFAULT NULL, birthday DATETIME DEFAULT NULL, gender TINYINT(1) DEFAULT NULL, status LONGTEXT DEFAULT NULL, city LONGTEXT DEFAULT NULL, job LONGTEXT DEFAULT NULL, facebook LONGTEXT DEFAULT NULL, twitter LONGTEXT DEFAULT NULL, relationship_accepted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_C087935A76ED395 (user_id), UNIQUE INDEX UNIQ_C087935937FE91C (relationship_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, new_email VARCHAR(255) DEFAULT NULL, first_time TINYINT(1) NOT NULL, show_interest_notification TINYINT(1) NOT NULL, is_private TINYINT(1) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, facebook_access_token VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, emailNotification TINYINT(1) NOT NULL, new_email_token LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A64791F043FA9 (new_email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE categories_users (user_id INT NOT NULL, category_id VARCHAR(255) NOT NULL, INDEX IDX_1080B0A4A76ED395 (user_id), INDEX IDX_1080B0A412469DE2 (category_id), PRIMARY KEY(user_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE posts_images (id VARCHAR(255) NOT NULL, post_id INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8A1D99C34B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE vendor_infos (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, company_name LONGTEXT NOT NULL, info LONGTEXT DEFAULT NULL, mobile LONGTEXT DEFAULT NULL, email LONGTEXT DEFAULT NULL, twitter LONGTEXT DEFAULT NULL, facebook LONGTEXT DEFAULT NULL, website LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_F88C802DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE categories_vendors (vendor_id VARCHAR(255) NOT NULL, category_id VARCHAR(255) NOT NULL, INDEX IDX_672F6BB1F603EE73 (vendor_id), INDEX IDX_672F6BB112469DE2 (category_id), PRIMARY KEY(vendor_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE follow_users (id VARCHAR(255) NOT NULL, follower_id INT DEFAULT NULL, followee_id INT DEFAULT NULL, is_approved TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_1674F53AC24F853 (follower_id), INDEX IDX_1674F5361EA9775 (followee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, video LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, INDEX IDX_885DBAFAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE searches (id VARCHAR(255) NOT NULL, search_text LONGTEXT NOT NULL, search_filter LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', search_sort LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', search_type LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE wishlists_images (id VARCHAR(255) NOT NULL, wishlist_id VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_28F9F88AFB8E54CD (wishlist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE experiences (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, title LONGTEXT NOT NULL, content LONGTEXT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_82020E70A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE experience_category (experience_id VARCHAR(255) NOT NULL, category_id VARCHAR(255) NOT NULL, INDEX IDX_1808C04246E90E27 (experience_id), INDEX IDX_1808C04212469DE2 (category_id), PRIMARY KEY(experience_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE conf_reads (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, conf_id VARCHAR(255) DEFAULT NULL, read_status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_843D73A8A76ED395 (user_id), INDEX IDX_843D73A87FDF4958 (conf_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE likes (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, notification_id VARCHAR(255) DEFAULT NULL, object_id LONGTEXT NOT NULL, object_type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_49CA4E7DA76ED395 (user_id), UNIQUE INDEX UNIQ_49CA4E7DEF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE users_cover_photos (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_DB8E4398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE products (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, name LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(55, 2) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B3BA5A5AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE product_tag (product_id VARCHAR(255) NOT NULL, tag_id VARCHAR(255) NOT NULL, INDEX IDX_E3A6E39C4584665A (product_id), INDEX IDX_E3A6E39CBAD26311 (tag_id), PRIMARY KEY(product_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE wishlist_products (wishlist_id VARCHAR(255) NOT NULL, product_id VARCHAR(255) NOT NULL, INDEX IDX_3F5CEAEFB8E54CD (wishlist_id), INDEX IDX_3F5CEAE4584665A (product_id), PRIMARY KEY(wishlist_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE tags (id VARCHAR(255) NOT NULL, name LONGTEXT NOT NULL, name_slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6FBC9426DF2B4115 (name_slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE tag_product (tag_id VARCHAR(255) NOT NULL, product_id VARCHAR(255) NOT NULL, INDEX IDX_E17B2907BAD26311 (tag_id), INDEX IDX_E17B29074584665A (product_id), PRIMARY KEY(tag_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE wishlists (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, name LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_4A4C2E1BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE users_profile_photos (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1EE41E0BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE albums (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, name LONGTEXT DEFAULT NULL, info LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F4E2474FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE confs (id VARCHAR(255) NOT NULL, user_one_id INT DEFAULT NULL, user_two_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C31717589EC8D52E (user_one_id), INDEX IDX_C3171758F59432E1 (user_two_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE photos (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, album_id VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, caption LONGTEXT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_876E0D9A76ED395 (user_id), INDEX IDX_876E0D91137ABCF (album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE notifications (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, other_end_id INT DEFAULT NULL, content LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)', is_read TINYINT(1) NOT NULL, action_id LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_6000B0D3A76ED395 (user_id), INDEX IDX_6000B0D3D3BBB008 (other_end_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE comments (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, notification_id VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, is_removed TINYINT(1) NOT NULL, object_id LONGTEXT NOT NULL, object_type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_5F9E962AA76ED395 (user_id), UNIQUE INDEX UNIQ_5F9E962AEF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE conf_replies (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, conf_id VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B6088FBAA76ED395 (user_id), INDEX IDX_B6088FBA7FDF4958 (conf_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE branches (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, address LONGTEXT NOT NULL, city LONGTEXT NOT NULL, phone LONGTEXT DEFAULT NULL, mobile LONGTEXT DEFAULT NULL, email LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_D760D16FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE categories (id VARCHAR(255) NOT NULL, parent_category_id VARCHAR(255) DEFAULT NULL, name LONGTEXT NOT NULL, name_slug VARCHAR(255) NOT NULL, css_class LONGTEXT NOT NULL, is_hidden TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_3AF34668DF2B4115 (name_slug), INDEX IDX_3AF34668796A8F92 (parent_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE acl_classes (id INT UNSIGNED AUTO_INCREMENT NOT NULL, class_type VARCHAR(200) NOT NULL, UNIQUE INDEX UNIQ_69DD750638A36066 (class_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE acl_security_identities (id INT UNSIGNED AUTO_INCREMENT NOT NULL, identifier VARCHAR(200) NOT NULL, username TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8835EE78772E836AF85E0677 (identifier, username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE acl_object_identities (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_object_identity_id INT UNSIGNED DEFAULT NULL, class_id INT UNSIGNED NOT NULL, object_identifier VARCHAR(100) NOT NULL, entries_inheriting TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9407E5494B12AD6EA000B10 (object_identifier, class_id), INDEX IDX_9407E54977FA751A (parent_object_identity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE acl_object_identity_ancestors (object_identity_id INT UNSIGNED NOT NULL, ancestor_id INT UNSIGNED NOT NULL, INDEX IDX_825DE2993D9AB4A6 (object_identity_id), INDEX IDX_825DE299C671CEA1 (ancestor_id), PRIMARY KEY(object_identity_id, ancestor_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE acl_entries (id INT UNSIGNED AUTO_INCREMENT NOT NULL, class_id INT UNSIGNED NOT NULL, object_identity_id INT UNSIGNED DEFAULT NULL, security_identity_id INT UNSIGNED NOT NULL, field_name VARCHAR(50) DEFAULT NULL, ace_order SMALLINT UNSIGNED NOT NULL, mask INT NOT NULL, granting TINYINT(1) NOT NULL, granting_strategy VARCHAR(30) NOT NULL, audit_success TINYINT(1) NOT NULL, audit_failure TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4 (class_id, object_identity_id, field_name, ace_order), INDEX IDX_46C8B806EA000B103D9AB4A6DF9183C9 (class_id, object_identity_id, security_identity_id), INDEX IDX_46C8B806EA000B10 (class_id), INDEX IDX_46C8B8063D9AB4A6 (object_identity_id), INDEX IDX_46C8B806DF9183C9 (security_identity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE MessageMetadata ADD CONSTRAINT FK_DA67B3AD537A1329 FOREIGN KEY (message_id) REFERENCES Message (id)");
        $this->addSql("ALTER TABLE MessageMetadata ADD CONSTRAINT FK_DA67B3AD9D1C3019 FOREIGN KEY (participant_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE Message ADD CONSTRAINT FK_790009E3E2904019 FOREIGN KEY (thread_id) REFERENCES Thread (id)");
        $this->addSql("ALTER TABLE Message ADD CONSTRAINT FK_790009E3F624B39D FOREIGN KEY (sender_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE ThreadMetadata ADD CONSTRAINT FK_BEF427AEE2904019 FOREIGN KEY (thread_id) REFERENCES Thread (id)");
        $this->addSql("ALTER TABLE ThreadMetadata ADD CONSTRAINT FK_BEF427AE9D1C3019 FOREIGN KEY (participant_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE Thread ADD CONSTRAINT FK_368C49B53174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE tips ADD CONSTRAINT FK_642C4108A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE tip_category ADD CONSTRAINT FK_4FCC19CE476C47F6 FOREIGN KEY (tip_id) REFERENCES tips (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE tip_category ADD CONSTRAINT FK_4FCC19CE12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE user_infos ADD CONSTRAINT FK_C087935A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE user_infos ADD CONSTRAINT FK_C087935937FE91C FOREIGN KEY (relationship_user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE categories_users ADD CONSTRAINT FK_1080B0A4A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE categories_users ADD CONSTRAINT FK_1080B0A412469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE posts_images ADD CONSTRAINT FK_8A1D99C34B89032C FOREIGN KEY (post_id) REFERENCES posts (id)");
        $this->addSql("ALTER TABLE vendor_infos ADD CONSTRAINT FK_F88C802DA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE categories_vendors ADD CONSTRAINT FK_672F6BB1F603EE73 FOREIGN KEY (vendor_id) REFERENCES vendor_infos (id)");
        $this->addSql("ALTER TABLE categories_vendors ADD CONSTRAINT FK_672F6BB112469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)");
        $this->addSql("ALTER TABLE follow_users ADD CONSTRAINT FK_1674F53AC24F853 FOREIGN KEY (follower_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE follow_users ADD CONSTRAINT FK_1674F5361EA9775 FOREIGN KEY (followee_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE wishlists_images ADD CONSTRAINT FK_28F9F88AFB8E54CD FOREIGN KEY (wishlist_id) REFERENCES wishlists (id)");
        $this->addSql("ALTER TABLE experiences ADD CONSTRAINT FK_82020E70A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE experience_category ADD CONSTRAINT FK_1808C04246E90E27 FOREIGN KEY (experience_id) REFERENCES experiences (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE experience_category ADD CONSTRAINT FK_1808C04212469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE conf_reads ADD CONSTRAINT FK_843D73A8A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE conf_reads ADD CONSTRAINT FK_843D73A87FDF4958 FOREIGN KEY (conf_id) REFERENCES confs (id)");
        $this->addSql("ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notifications (id)");
        $this->addSql("ALTER TABLE users_cover_photos ADD CONSTRAINT FK_DB8E4398A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39C4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39CBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE wishlist_products ADD CONSTRAINT FK_3F5CEAEFB8E54CD FOREIGN KEY (wishlist_id) REFERENCES products (id)");
        $this->addSql("ALTER TABLE wishlist_products ADD CONSTRAINT FK_3F5CEAE4584665A FOREIGN KEY (product_id) REFERENCES wishlists (id)");
        $this->addSql("ALTER TABLE tag_product ADD CONSTRAINT FK_E17B2907BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE tag_product ADD CONSTRAINT FK_E17B29074584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE wishlists ADD CONSTRAINT FK_4A4C2E1BA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE users_profile_photos ADD CONSTRAINT FK_1EE41E0BA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE albums ADD CONSTRAINT FK_F4E2474FA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE confs ADD CONSTRAINT FK_C31717589EC8D52E FOREIGN KEY (user_one_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE confs ADD CONSTRAINT FK_C3171758F59432E1 FOREIGN KEY (user_two_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE photos ADD CONSTRAINT FK_876E0D9A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE photos ADD CONSTRAINT FK_876E0D91137ABCF FOREIGN KEY (album_id) REFERENCES albums (id)");
        $this->addSql("ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3D3BBB008 FOREIGN KEY (other_end_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notifications (id)");
        $this->addSql("ALTER TABLE conf_replies ADD CONSTRAINT FK_B6088FBAA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE conf_replies ADD CONSTRAINT FK_B6088FBA7FDF4958 FOREIGN KEY (conf_id) REFERENCES confs (id)");
        $this->addSql("ALTER TABLE branches ADD CONSTRAINT FK_D760D16FA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE categories ADD CONSTRAINT FK_3AF34668796A8F92 FOREIGN KEY (parent_category_id) REFERENCES categories (id)");
        $this->addSql("ALTER TABLE acl_object_identities ADD CONSTRAINT FK_9407E54977FA751A FOREIGN KEY (parent_object_identity_id) REFERENCES acl_object_identities (id)");
        $this->addSql("ALTER TABLE acl_object_identity_ancestors ADD CONSTRAINT FK_825DE2993D9AB4A6 FOREIGN KEY (object_identity_id) REFERENCES acl_object_identities (id) ON UPDATE CASCADE ON DELETE CASCADE");
        $this->addSql("ALTER TABLE acl_object_identity_ancestors ADD CONSTRAINT FK_825DE299C671CEA1 FOREIGN KEY (ancestor_id) REFERENCES acl_object_identities (id) ON UPDATE CASCADE ON DELETE CASCADE");
        $this->addSql("ALTER TABLE acl_entries ADD CONSTRAINT FK_46C8B806EA000B10 FOREIGN KEY (class_id) REFERENCES acl_classes (id) ON UPDATE CASCADE ON DELETE CASCADE");
        $this->addSql("ALTER TABLE acl_entries ADD CONSTRAINT FK_46C8B8063D9AB4A6 FOREIGN KEY (object_identity_id) REFERENCES acl_object_identities (id) ON UPDATE CASCADE ON DELETE CASCADE");
        $this->addSql("ALTER TABLE acl_entries ADD CONSTRAINT FK_46C8B806DF9183C9 FOREIGN KEY (security_identity_id) REFERENCES acl_security_identities (id) ON UPDATE CASCADE ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE MessageMetadata DROP FOREIGN KEY FK_DA67B3AD537A1329");
        $this->addSql("ALTER TABLE Message DROP FOREIGN KEY FK_790009E3E2904019");
        $this->addSql("ALTER TABLE ThreadMetadata DROP FOREIGN KEY FK_BEF427AEE2904019");
        $this->addSql("ALTER TABLE tip_category DROP FOREIGN KEY FK_4FCC19CE476C47F6");
        $this->addSql("ALTER TABLE MessageMetadata DROP FOREIGN KEY FK_DA67B3AD9D1C3019");
        $this->addSql("ALTER TABLE Message DROP FOREIGN KEY FK_790009E3F624B39D");
        $this->addSql("ALTER TABLE ThreadMetadata DROP FOREIGN KEY FK_BEF427AE9D1C3019");
        $this->addSql("ALTER TABLE Thread DROP FOREIGN KEY FK_368C49B53174800F");
        $this->addSql("ALTER TABLE tips DROP FOREIGN KEY FK_642C4108A76ED395");
        $this->addSql("ALTER TABLE user_infos DROP FOREIGN KEY FK_C087935A76ED395");
        $this->addSql("ALTER TABLE user_infos DROP FOREIGN KEY FK_C087935937FE91C");
        $this->addSql("ALTER TABLE categories_users DROP FOREIGN KEY FK_1080B0A4A76ED395");
        $this->addSql("ALTER TABLE vendor_infos DROP FOREIGN KEY FK_F88C802DA76ED395");
        $this->addSql("ALTER TABLE follow_users DROP FOREIGN KEY FK_1674F53AC24F853");
        $this->addSql("ALTER TABLE follow_users DROP FOREIGN KEY FK_1674F5361EA9775");
        $this->addSql("ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAA76ED395");
        $this->addSql("ALTER TABLE experiences DROP FOREIGN KEY FK_82020E70A76ED395");
        $this->addSql("ALTER TABLE conf_reads DROP FOREIGN KEY FK_843D73A8A76ED395");
        $this->addSql("ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DA76ED395");
        $this->addSql("ALTER TABLE users_cover_photos DROP FOREIGN KEY FK_DB8E4398A76ED395");
        $this->addSql("ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AA76ED395");
        $this->addSql("ALTER TABLE wishlists DROP FOREIGN KEY FK_4A4C2E1BA76ED395");
        $this->addSql("ALTER TABLE users_profile_photos DROP FOREIGN KEY FK_1EE41E0BA76ED395");
        $this->addSql("ALTER TABLE albums DROP FOREIGN KEY FK_F4E2474FA76ED395");
        $this->addSql("ALTER TABLE confs DROP FOREIGN KEY FK_C31717589EC8D52E");
        $this->addSql("ALTER TABLE confs DROP FOREIGN KEY FK_C3171758F59432E1");
        $this->addSql("ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9A76ED395");
        $this->addSql("ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395");
        $this->addSql("ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3D3BBB008");
        $this->addSql("ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395");
        $this->addSql("ALTER TABLE conf_replies DROP FOREIGN KEY FK_B6088FBAA76ED395");
        $this->addSql("ALTER TABLE branches DROP FOREIGN KEY FK_D760D16FA76ED395");
        $this->addSql("ALTER TABLE categories_vendors DROP FOREIGN KEY FK_672F6BB1F603EE73");
        $this->addSql("ALTER TABLE posts_images DROP FOREIGN KEY FK_8A1D99C34B89032C");
        $this->addSql("ALTER TABLE experience_category DROP FOREIGN KEY FK_1808C04246E90E27");
        $this->addSql("ALTER TABLE product_tag DROP FOREIGN KEY FK_E3A6E39C4584665A");
        $this->addSql("ALTER TABLE wishlist_products DROP FOREIGN KEY FK_3F5CEAEFB8E54CD");
        $this->addSql("ALTER TABLE tag_product DROP FOREIGN KEY FK_E17B29074584665A");
        $this->addSql("ALTER TABLE product_tag DROP FOREIGN KEY FK_E3A6E39CBAD26311");
        $this->addSql("ALTER TABLE tag_product DROP FOREIGN KEY FK_E17B2907BAD26311");
        $this->addSql("ALTER TABLE wishlists_images DROP FOREIGN KEY FK_28F9F88AFB8E54CD");
        $this->addSql("ALTER TABLE wishlist_products DROP FOREIGN KEY FK_3F5CEAE4584665A");
        $this->addSql("ALTER TABLE photos DROP FOREIGN KEY FK_876E0D91137ABCF");
        $this->addSql("ALTER TABLE conf_reads DROP FOREIGN KEY FK_843D73A87FDF4958");
        $this->addSql("ALTER TABLE conf_replies DROP FOREIGN KEY FK_B6088FBA7FDF4958");
        $this->addSql("ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DEF1A9D84");
        $this->addSql("ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AEF1A9D84");
        $this->addSql("ALTER TABLE tip_category DROP FOREIGN KEY FK_4FCC19CE12469DE2");
        $this->addSql("ALTER TABLE categories_users DROP FOREIGN KEY FK_1080B0A412469DE2");
        $this->addSql("ALTER TABLE categories_vendors DROP FOREIGN KEY FK_672F6BB112469DE2");
        $this->addSql("ALTER TABLE experience_category DROP FOREIGN KEY FK_1808C04212469DE2");
        $this->addSql("ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668796A8F92");
        $this->addSql("ALTER TABLE acl_entries DROP FOREIGN KEY FK_46C8B806EA000B10");
        $this->addSql("ALTER TABLE acl_entries DROP FOREIGN KEY FK_46C8B806DF9183C9");
        $this->addSql("ALTER TABLE acl_object_identities DROP FOREIGN KEY FK_9407E54977FA751A");
        $this->addSql("ALTER TABLE acl_object_identity_ancestors DROP FOREIGN KEY FK_825DE2993D9AB4A6");
        $this->addSql("ALTER TABLE acl_object_identity_ancestors DROP FOREIGN KEY FK_825DE299C671CEA1");
        $this->addSql("ALTER TABLE acl_entries DROP FOREIGN KEY FK_46C8B8063D9AB4A6");
        $this->addSql("DROP TABLE MessageMetadata");
        $this->addSql("DROP TABLE Message");
        $this->addSql("DROP TABLE ThreadMetadata");
        $this->addSql("DROP TABLE Thread");
        $this->addSql("DROP TABLE tips");
        $this->addSql("DROP TABLE tip_category");
        $this->addSql("DROP TABLE user_infos");
        $this->addSql("DROP TABLE fos_user");
        $this->addSql("DROP TABLE categories_users");
        $this->addSql("DROP TABLE posts_images");
        $this->addSql("DROP TABLE vendor_infos");
        $this->addSql("DROP TABLE categories_vendors");
        $this->addSql("DROP TABLE follow_users");
        $this->addSql("DROP TABLE posts");
        $this->addSql("DROP TABLE searches");
        $this->addSql("DROP TABLE wishlists_images");
        $this->addSql("DROP TABLE experiences");
        $this->addSql("DROP TABLE experience_category");
        $this->addSql("DROP TABLE conf_reads");
        $this->addSql("DROP TABLE likes");
        $this->addSql("DROP TABLE users_cover_photos");
        $this->addSql("DROP TABLE products");
        $this->addSql("DROP TABLE product_tag");
        $this->addSql("DROP TABLE wishlist_products");
        $this->addSql("DROP TABLE tags");
        $this->addSql("DROP TABLE tag_product");
        $this->addSql("DROP TABLE wishlists");
        $this->addSql("DROP TABLE users_profile_photos");
        $this->addSql("DROP TABLE albums");
        $this->addSql("DROP TABLE confs");
        $this->addSql("DROP TABLE photos");
        $this->addSql("DROP TABLE notifications");
        $this->addSql("DROP TABLE comments");
        $this->addSql("DROP TABLE conf_replies");
        $this->addSql("DROP TABLE branches");
        $this->addSql("DROP TABLE categories");
        $this->addSql("DROP TABLE acl_classes");
        $this->addSql("DROP TABLE acl_security_identities");
        $this->addSql("DROP TABLE acl_object_identities");
        $this->addSql("DROP TABLE acl_object_identity_ancestors");
        $this->addSql("DROP TABLE acl_entries");
    }
}
