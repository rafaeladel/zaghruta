<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141204135917 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE deleted_messages (id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("DROP TABLE message_user");
        $this->addSql("ALTER TABLE Message ADD delete_table_id VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE Message ADD CONSTRAINT FK_790009E36E542D49 FOREIGN KEY (delete_table_id) REFERENCES deleted_messages (id)");
        $this->addSql("CREATE INDEX IDX_790009E36E542D49 ON Message (delete_table_id)");
        $this->addSql("ALTER TABLE fos_user ADD delete_table_id VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE fos_user ADD CONSTRAINT FK_957A64796E542D49 FOREIGN KEY (delete_table_id) REFERENCES deleted_messages (id)");
        $this->addSql("CREATE INDEX IDX_957A64796E542D49 ON fos_user (delete_table_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Message DROP FOREIGN KEY FK_790009E36E542D49");
        $this->addSql("ALTER TABLE fos_user DROP FOREIGN KEY FK_957A64796E542D49");
        $this->addSql("CREATE TABLE message_user (message_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_24064D90537A1329 (message_id), INDEX IDX_24064D90A76ED395 (user_id), PRIMARY KEY(message_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE message_user ADD CONSTRAINT FK_24064D90537A1329 FOREIGN KEY (message_id) REFERENCES Message (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE message_user ADD CONSTRAINT FK_24064D90A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE");
        $this->addSql("DROP TABLE deleted_messages");
        $this->addSql("DROP INDEX IDX_790009E36E542D49 ON Message");
        $this->addSql("ALTER TABLE Message DROP delete_table_id");
        $this->addSql("DROP INDEX IDX_957A64796E542D49 ON fos_user");
        $this->addSql("ALTER TABLE fos_user DROP delete_table_id");
    }
}
