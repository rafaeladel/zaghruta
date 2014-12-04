<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141204141704 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Message DROP FOREIGN KEY FK_790009E36E542D49");
        $this->addSql("DROP INDEX IDX_790009E36E542D49 ON Message");
        $this->addSql("ALTER TABLE Message DROP delete_table_id");
        $this->addSql("ALTER TABLE deleted_messages ADD message_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE deleted_messages ADD CONSTRAINT FK_88E6DD7537A1329 FOREIGN KEY (message_id) REFERENCES Message (id)");
        $this->addSql("ALTER TABLE deleted_messages ADD CONSTRAINT FK_88E6DD7A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("CREATE INDEX IDX_88E6DD7537A1329 ON deleted_messages (message_id)");
        $this->addSql("CREATE INDEX IDX_88E6DD7A76ED395 ON deleted_messages (user_id)");
        $this->addSql("ALTER TABLE fos_user DROP FOREIGN KEY FK_957A64796E542D49");
        $this->addSql("DROP INDEX IDX_957A64796E542D49 ON fos_user");
        $this->addSql("ALTER TABLE fos_user DROP delete_table_id");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Message ADD delete_table_id VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE Message ADD CONSTRAINT FK_790009E36E542D49 FOREIGN KEY (delete_table_id) REFERENCES deleted_messages (id)");
        $this->addSql("CREATE INDEX IDX_790009E36E542D49 ON Message (delete_table_id)");
        $this->addSql("ALTER TABLE deleted_messages DROP FOREIGN KEY FK_88E6DD7537A1329");
        $this->addSql("ALTER TABLE deleted_messages DROP FOREIGN KEY FK_88E6DD7A76ED395");
        $this->addSql("DROP INDEX IDX_88E6DD7537A1329 ON deleted_messages");
        $this->addSql("DROP INDEX IDX_88E6DD7A76ED395 ON deleted_messages");
        $this->addSql("ALTER TABLE deleted_messages DROP message_id, DROP user_id");
        $this->addSql("ALTER TABLE fos_user ADD delete_table_id VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE fos_user ADD CONSTRAINT FK_957A64796E542D49 FOREIGN KEY (delete_table_id) REFERENCES deleted_messages (id)");
        $this->addSql("CREATE INDEX IDX_957A64796E542D49 ON fos_user (delete_table_id)");
    }
}
